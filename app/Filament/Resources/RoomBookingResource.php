<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomBookingResource\Pages;
use App\Filament\Resources\RoomBookingResource\RelationManagers;
use App\Models\RoomBooking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomBookingResource extends Resource
{
    protected static ?string $model = RoomBooking::class;
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $navigationGroup = 'Thuê phòng học';

    public static function getModelLabel(): string
    {
        return 'đặt phòng';
    }
    public static function getPluralModelLabel(): string
    {
        return 'đặt phòng';
    }
    public static function getNavigationLabel(): string
    {
        return 'Yêu cầu đặt phòng';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin cơ bản')
                    ->description('Nhập thông tin cơ bản của yêu cầu đặt phòng')
                    ->schema([
                        Forms\Components\select::make('booking_group_id')
                            ->label('Nhóm đặt phòng')
                            ->relationship('room_booking_group', 'title'),
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
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('Thời gian và mục đích')
                    ->description('Thông tin về thời gian và mục đích sử dụng')
                    ->schema([
                        Forms\Components\DatePicker::make('booking_date')
                            ->label('Ngày đặt')
                            ->displayFormat('d/m/Y')
                            ->minDate(now())
                            ->native(false)
                            ->required(),
                        Forms\Components\TimePicker::make('start_time')
                            ->label('Thời gian bắt đầu')
                            ->displayFormat('H:i')
                            ->seconds(false)
                            ->native(false)
                            ->required(),
                        Forms\Components\TimePicker::make('end_time')
                            ->label('Thời gian kết thúc')
                            ->displayFormat('H:i')
                            ->native(false)
                            ->seconds(false)
                            ->required(),
                        Forms\Components\TextInput::make('purpose')
                            ->label('Mục đích')
                            ->maxLength(255)
                            ->default(null),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('Cài đặt khác')
                    ->description('Các cài đặt bổ sung')
                    ->schema([
                        Forms\Components\Toggle::make('is_recurring')
                            ->label('Lặp lại'),
                        Forms\Components\select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'pending' => 'Đang chờ',
                                'approved' => 'Đã phê duyệt',
                                'rejected' => 'Đã từ chối',
                                'cancelled' => 'Đã hủy',
                            ])
                            ->required(),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('room_booking_group.title')
                    ->label('Nhóm đặt phòng')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Người dùng')
                    ->sortable(),
                Tables\Columns\TextColumn::make('room.name')
                    ->label('Phòng')
                    ->sortable(),
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Khóa học')
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking_date')
                    ->label('Ngày đặt')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Thời gian bắt đầu')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Thời gian kết thúc')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('purpose')
                    ->label('Mục đích')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_recurring')
                    ->label('Lặp lại')
                    ->boolean(),
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
            'index' => Pages\ListRoomBookings::route('/'),
            'create' => Pages\CreateRoomBooking::route('/create'),
            'edit' => Pages\EditRoomBooking::route('/{record}/edit'),
        ];
    }
}
