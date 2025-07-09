<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('khóa học');
    }
    public static function getPluralModelLabel(): string
    {
        return __('khóa học');
    }
    public static function getNavigationLabel(): string
    {
        return __('Quản lý khóa học');
    }
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->label(__('Tiêu đề khóa học'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->label(__('Slug khóa học'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label(__('Mô tả khóa học'))
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('content')
                    ->label(__('Nội dung khóa học'))
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('featured_image')
                    ->label(__('Hình ảnh nổi bật'))
                    ->image(),
                Forms\Components\TextInput::make('price')
                    ->label(__('Giá khóa học'))
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),
                Forms\Components\Select::make('category_id')
                    ->label(__('Danh mục khóa học'))
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('end_registration_date')
                    ->label(__('Ngày kết thúc đăng ký'))
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->label(__('Ngày bắt đầu'))
                    ->required(),
                Forms\Components\Select::make('status')
                    ->required()
                    ->label(__('Trạng thái'))
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Tiêu đề khóa học'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label(__('Slug khóa học'))
                    ->searchable(),
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label(__('Hình ảnh nổi bật')),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('Giá khóa học'))
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category_id')
                    ->numeric()
                    ->label(__('Danh mục khóa học'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_registration_date')
                    ->date()
                    ->label(__('Ngày kết thúc đăng ký'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->label(__('Ngày bắt đầu'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('Trạng thái')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label(__('Ngày tạo'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->label(__('Ngày cập nhật'))
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
