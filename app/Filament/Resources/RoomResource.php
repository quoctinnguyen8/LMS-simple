<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Filament\Resources\RoomResource\RelationManagers;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('Phòng học');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Phòng học');
    }
    public static function getNavigationLabel(): string
    {
        return __('Quản lý phòng học');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Tên phòng học'))
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('capacity')
                    ->label(__('Sức chứa'))
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('location')
                    ->label(__('Vị trí'))
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Select::make('status')
                    ->required()
                    ->label(__('Trạng thái'))
                    ->options([
                        'available' => __('Có sẵn'),
                        'unavailable' => __('Không có sẵn'),
                        'maintenance' => __('Bảo trì'),
                    ]),
                Forms\Components\Select::make('equipments')
                    ->relationship('equipment', 'name')
                    ->multiple()
                    ->preload()
                    ->label(__('Thiết bị')),
                Forms\Components\Textarea::make('description')
                    ->label(__('Mô tả'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Tên phòng học'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->label(__('Sức chứa'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->label(__('Vị trí'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('Trạng thái'))
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'available' => __('Có sẵn'),
                        'unavailable' => __('Không có sẵn'),
                        'maintenance' => __('Bảo trì'),
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
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
