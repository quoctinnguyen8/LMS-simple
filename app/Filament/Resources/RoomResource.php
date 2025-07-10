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

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    
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
                Forms\Components\TextInput::make('name')
                    ->label('Tên phòng học')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('capacity')
                    ->label('Sức chứa')
                    ->required()
                    ->numeric(),
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
                Forms\Components\Select::make('equipments')
                    ->relationship('equipment', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Thiết bị'),
                Forms\Components\Textarea::make('description')
                    ->label('Mô tả')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên phòng học')
                    ->searchable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->label('Sức chứa')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Vị trí')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
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
