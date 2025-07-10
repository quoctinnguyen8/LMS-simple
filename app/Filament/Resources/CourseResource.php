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

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function getModelLabel(): string
    {
        return 'khóa học';
    }
    public static function getPluralModelLabel(): string
    {
        return 'khóa học';
    }
    public static function getNavigationLabel(): string
    {
        return 'Quản lý khóa học';
    }
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->label('Tiêu đề khóa học')
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug khóa học')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Mô tả khóa học')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('content')
                    ->label('Nội dung khóa học')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('featured_image')
                    ->label('Hình ảnh nổi bật')
                    ->disk('public')
                    ->image(),
                Forms\Components\TextInput::make('price')
                    ->label('Giá khóa học')
                    ->required()
                    ->numeric()
                    ->default(null)
                    ->prefix('₫'),
                Forms\Components\Select::make('category_id')
                    ->label('Danh mục khóa học')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('end_registration_date')
                    ->label('Ngày kết thúc đăng ký')
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Ngày bắt đầu')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->required()
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Nháp',
                        'published' => 'Đã xuất bản',
                        'archived' => 'Đã lưu trữ',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề khóa học')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug khóa học')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Hình ảnh nổi bật'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Giá khóa học')
                    ->money('VND')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Danh mục khóa học')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_registration_date')
                    ->date()
                    ->label('Ngày kết thúc đăng ký')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->label('Ngày bắt đầu')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Nháp',
                        'published' => 'Đã xuất bản',
                        'archived' => 'Đã lưu trữ',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->label('Ngày tạo')
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
