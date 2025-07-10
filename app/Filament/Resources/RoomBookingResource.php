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

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function getModelLabel(): string
    {
        return __('Đặt phòng');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Đặt phòng');
    }
    public static function getNavigationLabel(): string
    {
        return __('Quản lý đặt phòng');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\select::make('booking_group_id')
                    ->label(__('Nhóm đặt phòng'))
                    ->relationship('room_booking_group', 'title'),
                Forms\Components\select::make('user_id')
                    ->label(__('Người dùng'))
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\select::make('room_id')
                    ->label(__('Phòng'))
                    ->relationship('room', 'name')
                    ->required(),
                Forms\Components\select::make('course_id')
                    ->label(__('Khóa học'))
                    ->relationship('course', 'title')
                    ->required(),
                Forms\Components\DatePicker::make('booking_date')
                    ->label(__('Ngày đặt'))
                    ->required(),
                Forms\Components\TextInput::make('start_time')
                    ->label(__('Thời gian bắt đầu'))
                    ->required(),
                Forms\Components\TextInput::make('end_time')
                    ->label(__('Thời gian kết thúc'))
                    ->required(),
                Forms\Components\TextInput::make('purpose')
                    ->label(__('Mục đích'))
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Toggle::make('is_recurring')
                    ->label(__('Lặp lại')),
                Forms\Components\select::make('status')
                    ->label(__('Trạng thái'))
                    ->options([
                        'pending' => __('Đang chờ'),
                        'approved' => __('Đã phê duyệt'),
                        'rejected' => __('Đã từ chối'),
                        'cancelled' => __('Đã hủy'),
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('room_booking_group.title')
                    ->label(__('Nhóm đặt phòng'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Người dùng'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('room.name')
                    ->label(__('Phòng'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('course.title')
                    ->label(__('Khóa học'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking_date')
                    ->label(__('Ngày đặt'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label(__('Thời gian bắt đầu'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label(__('Thời gian kết thúc'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('purpose')
                    ->label(__('Mục đích'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_recurring')
                    ->label(__('Lặp lại'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('Trạng thái'))
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => __('Đang chờ'),
                        'approved' => __('Đã phê duyệt'),
                        'rejected' => __('Đã từ chối'),
                        'cancelled' => __('Đã hủy'),
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Ngày tạo'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Ngày cập nhật'))
                    ->dateTime()
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
