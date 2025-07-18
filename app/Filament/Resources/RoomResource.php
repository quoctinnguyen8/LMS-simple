<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Filament\Resources\RoomResource\RelationManagers;
use App\Helpers\SettingHelper;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Thuê phòng học';

    public static function getModelLabel(): string
    {
        return 'phòng học';
    }
    public static function getPluralModelLabel(): string
    {
        return 'phòng học';
    }
    public static function getNavigationLabel(): string
    {
        return 'Phòng học';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin cơ bản')
                    ->description('Nhập thông tin cơ bản của phòng học')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Hình ảnh')
                            ->disk('public')
                            ->directory('room-images')
                            ->image()
                            ->imageEditor()
                            ->maxSize(2048) // 4MB
                            ->acceptedFileTypes(['image/*'])
                            ->helperText('Kích thước tối đa: 2MB. Định dạng: JPG, PNG, WebP,...')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('name')
                            ->label('Tên phòng học')
                            ->required()
                            ->maxLength(100)
                            ->columnSpanFull()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                $set('seo_title', $state);
                            }),
                        Forms\Components\TextInput::make('capacity')
                            ->label('Sức chứa tối đa (người)')
                            ->required()
                            ->integer(),
                        Forms\Components\TextInput::make('price')
                            ->label('Giá thuê')
                            ->helperText(function(Forms\Get $get) {
                                $unit = SettingHelper::getRoomRentalUnit();
                                $hUnit = SettingHelper::getRoomUnitToHour();
                                $msg =  "Đơn vị tính: " . SettingHelper::getRoomRentalUnit();
                                if ($unit != 'giờ') {
                                    $msg .= " (1 $unit =  $hUnit giờ)";
                                }
                                return $msg;
                            })
                            ->required()
                            ->integer()
                            ->prefix('₫')
                            ->numeric()
                            ->minValue(1000)
                            ->default(null)
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters([',', '.'])
                            ->dehydrateStateUsing(fn($state) => (int) str_replace([','], '', $state)),
                        Forms\Components\TextInput::make('location')
                            ->label('Vị trí')
                            ->maxLength(255)
                            ->default(null),
                        Forms\Components\Select::make('status')
                            ->required()
                            ->label('Trạng thái')
                            ->options([
                                'available' => 'Có sẵn',
                                'unavailable' => 'Không có sẵn',
                                'maintenance' => 'Bảo trì',
                            ]),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Thiết bị và mô tả')
                    ->description('Thiết bị có sẵn và mô tả chi tiết')
                    ->schema([
                        Forms\Components\Select::make('equipments')
                            ->relationship('equipment', 'name')
                            ->multiple()
                            ->preload()
                            ->optionsLimit(15)
                            ->label('Thiết bị có sẵn'),
                        Forms\Components\RichEditor::make('description')
                            ->label('Mô tả')
                            ->placeholder('Nhập mô tả chi tiết về phòng học')
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('room-attachments')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('SEO & Meta Data')
                    ->schema([
                        Forms\Components\TextInput::make('seo_image')
                            ->label('Ảnh SEO')
                            ->maxLength(500)
                            ->helperText('Ảnh được sử dụng khi chia sẻ trên mạng xã hội, để trống sẽ dùng ảnh bìa.'),

                        Forms\Components\TextInput::make('seo_title')
                            ->label('Tiêu đề SEO')
                            ->maxLength(500)
                            ->helperText('Tiêu đề tối ưu cho công cụ tìm kiếm'),

                        Forms\Components\Textarea::make('seo_description')
                            ->label('Mô tả SEO')
                            ->maxLength(2000)
                            ->rows(3)
                            ->helperText('Mô tả ngắn gọn cho công cụ tìm kiếm'),
                    ])
                    ->columns(1)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null) // Vô hiệu hóa click để chỉnh sửa
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Hình ảnh')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên phòng học')
                    ->searchable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->label('Sức chứa')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('equipment.name')
                    ->label('Thiết bị')
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('location')
                    ->label('Vị trí')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'available' => 'Có sẵn',
                        'unavailable' => 'Không có sẵn',
                        'maintenance' => 'Bảo trì',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
