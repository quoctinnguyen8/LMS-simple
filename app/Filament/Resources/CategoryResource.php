<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Khóa học';

    public static function getModelLabel(): string
    {
        return 'danh mục';
    }
    public static function getPluralModelLabel(): string
    {
        return 'Danh mục';
    }
    public static function getNavigationLabel(): string
    {
        return 'Danh mục khóa học';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin danh mục')
                    ->description('Nhập thông tin cơ bản của danh mục khóa học')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên danh mục')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->label('Slug danh mục')
                            ->maxLength(100),
                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả danh mục')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null) // Vô hiệu hóa click để chỉnh sửa
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Tên danh mục'),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug danh mục')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i')
                    ->label('Ngày cập nhật')
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
