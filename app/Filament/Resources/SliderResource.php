<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Filament\Resources\SliderResource\RelationManagers;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Quản lý Slider';

    protected static ?string $navigationGroup = 'Trang web';

    public static function getModelLabel(): string
    {
        return 'slider';
    }
    
    public static function getPluralModelLabel(): string
    {
        return 'Sliders';
    }
    
    public static function getNavigationLabel(): string
    {
        return 'Quản lý Slider';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Nhập thông tin của slider')
                    ->description('')
                    ->schema([
                        Forms\Components\Hidden::make('position')
                            ->label('Vị trí hiển thị')
                            ->required()
                            ->default(function() {
                                return \App\Models\Slider::max('position') + 1 ?? 1;
                            }),
                        Forms\Components\FileUpload::make('image_url')
                            ->label('Hình ảnh')
                            ->image()
                            ->required()
                            ->disk('public')
                            ->directory('sliders')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/*'])
                            ->helperText('Kích thước tối đa: 2MB. Khuyến nghị: 1920x600px')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('title')
                            ->label('Tiêu đề')
                            ->helperText('Kéo thả ở trang danh sách để sắp xếp thứ tự hiển thị của các slider')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Kích hoạt')
                            ->default(true)
                            ->inline(false)
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả ngắn')
                            ->maxLength(500)
                            ->helperText('Mô tả sẽ hiển thị trên slider')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('link_url')
                            ->label('Liên kết')
                            ->url()
                            ->placeholder('https://example.com')
                            ->helperText('URL sẽ được chuyển đến khi click vào slider')
                            ->columnSpan(2),
                        Forms\Components\DateTimePicker::make('start_date')
                            ->label('Ngày bắt đầu')
                            ->native(false)
                            ->displayFormat('d/m/Y H:i')
                            ->columnSpan(1),
                        Forms\Components\DateTimePicker::make('end_date')
                            ->label('Ngày kết thúc')
                            ->native(false)
                            ->displayFormat('d/m/Y H:i')
                            ->after('start_date')
                            ->columnSpan(1),
                    ])
                    ->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null) // Vô hiệu hóa click để chỉnh sửa
            ->description('Quản lý sliders hiển thị trên trang chủ. Bạn có thể kéo thả để sắp xếp thứ tự hiển thị của các slider.')
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Hình ảnh')
                    ->size(60)
                    ->square(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->description ? Str::limit($record->description, 50) : '')
                    ->wrap(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn ($record) => $record->status)
                    ->badge()
                    ->color(fn ($record): string => match ($record->status) {
                        'Đang hiển thị' => 'success',
                        'Chờ hiển thị' => 'warning',
                        'Hết hạn' => 'danger',
                        'Tắt' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Kích hoạt')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Bắt đầu')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Kết thúc')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Người tạo')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('position', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Trạng thái kích hoạt')
                    ->options([
                        true => 'Đang kích hoạt',
                        false => 'Đã tắt',
                    ]),
                Tables\Filters\Filter::make('currently_active')
                    ->label('Đang hiển thị')
                    ->query(fn (Builder $query) => $query->active()),
                Tables\Filters\Filter::make('expired')
                    ->label('Đã hết hạn')
                    ->query(fn (Builder $query) => $query->where('end_date', '<', now())),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->label('Sửa')
                        ->icon('heroicon-o-pencil-square'),
                    Tables\Actions\Action::make('toggle_active')
                        ->label(fn ($record) => $record->is_active ? 'Tắt' : 'Bật')
                        ->icon(fn ($record) => $record->is_active ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                        ->color(fn ($record) => $record->is_active ? 'danger' : 'success')
                        ->action(function ($record) {
                            $record->update(['is_active' => !$record->is_active]);
                        })
                        ->requiresConfirmation()
                        ->modalHeading(fn ($record) => ($record->is_active ? 'Tắt' : 'Bật') . ' slider'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Xóa')
                        ->icon('heroicon-o-trash'),
                ])
                ->icon('heroicon-o-ellipsis-vertical')
                ->color('gray')
                ->tooltip('Thao tác')
                ->extraAttributes(['class' => 'border'])
                ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Kích hoạt')
                        ->icon('heroicon-o-eye')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true])),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Tắt')
                        ->icon('heroicon-o-eye-slash')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false])),
                ]),
            ])
            ->reorderable('position')
            ->defaultPaginationPageOption(25)
            ->emptyStateHeading('Chưa có slider nào')
            ->emptyStateDescription('Tạo slider đầu tiên để bắt đầu.');
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
            'view' => Pages\ViewSlider::route('/{record}'),
        ];
    }
}
