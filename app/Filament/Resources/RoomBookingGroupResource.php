<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomBookingGroupResource\Pages;
use App\Filament\Resources\RoomBookingGroupResource\RelationManagers;
use App\Models\RoomBookingGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomBookingGroupResource extends Resource
{
    protected static ?string $model = RoomBookingGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Thuê phòng học';
    
    public static function getModelLabel(): string
    {
        return 'nhóm đặt phòng';
    }
    public static function getPluralModelLabel(): string
    {
        return 'nhóm đặt phòng';
    }
    public static function getNavigationLabel(): string
    {
        return 'Nhóm đặt phòng';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin cơ bản')
                    ->description('Nhập thông tin cơ bản của nhóm đặt phòng')
                    ->schema([
                        Forms\Components\select::make('user_id')
                            ->label('Người dùng')
                            ->relationship('user', 'name')
                            ->required(),
                        Forms\Components\select::make('room_id')
                            ->label('Phòng')
                            ->relationship('room', 'name')
                            ->required(),
                        Forms\Components\select::make('course_id')
                            ->label('Khóa học')
                            ->relationship('course', 'title')
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->label('Tiêu đề')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('purpose')
                            ->label('Mục đích')
                            ->maxLength(255)
                            ->default(null),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('Thời gian và lặp lại')
                    ->description('Cài đặt thời gian và chu kỳ lặp lại')
                    ->schema([
                        Forms\Components\TimePicker::make('start_time')
                            ->label('Thời gian bắt đầu')
                            ->required(),
                        Forms\Components\TimePicker::make('end_time')
                            ->label('Thời gian kết thúc')
                            ->required(),
                        Forms\Components\select::make('recurrence_type')
                            ->label('Loại lặp lại')
                            ->options([
                                'none' => '1 ngày',
                                'weekly' => 'Hàng tuần',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('recurrence_days')
                            ->label('Ngày lặp lại')
                            ->maxLength(20)
                            ->default(null),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('Thời gian hiệu lực')
                    ->description('Khoảng thời gian hiệu lực của nhóm đặt phòng')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Ngày bắt đầu')
                            ->required(),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Ngày kết thúc')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'pending' => 'Đang chờ',
                                'approved' => 'Đã phê duyệt',
                                'rejected' => 'Đã từ chối',
                                'cancelled' => 'Đã hủy',
                            ])
                            ->required(),
                    ])
                    ->columns(3)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Người dùng')
                    ->sortable(),
                Tables\Columns\TextColumn::make('room.name')
                    ->label('Phòng')
                    ->sortable(),
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Khóa học')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable(),
                Tables\Columns\TextColumn::make('purpose')
                    ->label('Mục đích')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Thời gian bắt đầu'),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Thời gian kết thúc'),
                Tables\Columns\TextColumn::make('recurrence_type')
                    ->label('Loại lặp lại')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'none' => '1 ngày',
                        'weekly' => 'Hàng tuần',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('recurrence_days')
                    ->label('Ngày lặp lại')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Ngày bắt đầu')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Ngày kết thúc')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Đang chờ',
                        'approved' => 'Đã phê duyệt',
                        'rejected' => 'Đã từ chối',
                        'cancelled' => 'Đã hủy',
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
            'index' => Pages\ListRoomBookingGroups::route('/'),
            'create' => Pages\CreateRoomBookingGroup::route('/create'),
            'edit' => Pages\EditRoomBookingGroup::route('/{record}/edit'),
        ];
    }
}
