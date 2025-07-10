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
    
    public static function getModelLabel(): string
    {
        return __('Nhóm đặt phòng');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Nhóm đặt phòng');
    }
    public static function getNavigationLabel(): string
    {
        return __('Quản lý nhóm đặt phòng');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Forms\Components\TextInput::make('title')
                    ->label(__('Tiêu đề'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('purpose')
                    ->label(__('Mục đích'))
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TimePicker::make('start_time')
                    ->label(__('Thời gian bắt đầu'))
                    ->required(),
                Forms\Components\TimePicker::make('end_time')
                    ->label(__('Thời gian kết thúc'))
                    ->required(),
                Forms\Components\select::make('recurrence_type')
                    ->label(__('Loại lặp lại'))
                    ->options([
                        'none' => __('1 ngày'),
                        'weekly' => __('Hàng tuần'),
                    ])
                    ->required(),
                Forms\Components\TextInput::make('recurrence_days')
                    ->label(__('Ngày lặp lại'))
                    ->maxLength(20)
                    ->default(null),
                Forms\Components\DatePicker::make('start_date')
                    ->label(__('Ngày bắt đầu'))
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->label(__('Ngày kết thúc'))
                    ->required(),
                Forms\Components\Select::make('status')
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
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Người dùng'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('room.name')
                    ->label(__('Phòng'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('course.title')
                    ->label(__('Khóa học'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Tiêu đề'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('purpose')
                    ->label(__('Mục đích'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label(__('Thời gian bắt đầu')),
                Tables\Columns\TextColumn::make('end_time')
                    ->label(__('Thời gian kết thúc')),
                Tables\Columns\TextColumn::make('recurrence_type')
                    ->label(__('Loại lặp lại'))
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'none' => __('1 ngày'),
                        'weekly' => __('Hàng tuần'),
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('recurrence_days')
                    ->label(__('Ngày lặp lại'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label(__('Ngày bắt đầu'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label(__('Ngày kết thúc'))
                    ->date()
                    ->sortable(),
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
            'index' => Pages\ListRoomBookingGroups::route('/'),
            'create' => Pages\CreateRoomBookingGroup::route('/create'),
            'edit' => Pages\EditRoomBookingGroup::route('/{record}/edit'),
        ];
    }
}
