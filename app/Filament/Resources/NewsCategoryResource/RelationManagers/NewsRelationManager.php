<?php

namespace App\Filament\Resources\NewsCategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class NewsRelationManager extends RelationManager
{
    protected static string $relationship = 'news';
    
    protected static ?string $title = 'Tin tức trong danh mục';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Tiêu đề')
                    ->required()
                    ->maxLength(500)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                    
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(500)
                    ->unique('news', 'slug', ignoreRecord: true),
                    
                Forms\Components\Textarea::make('summary')
                    ->label('Tóm tắt')
                    ->rows(3)
                    ->maxLength(1000),
                    
                Forms\Components\RichEditor::make('content')
                    ->label('Nội dung')
                    ->required()
                    ->columnSpanFull(),
                    
                Forms\Components\Select::make('author_id')
                    ->label('Tác giả')
                    ->relationship('user', 'name')
                    ->default(fn () => Auth::id())
                    ->required(),
                    
                Forms\Components\Toggle::make('is_published')
                    ->label('Xuất bản')
                    ->default(false),
                    
                Forms\Components\Toggle::make('is_featured')
                    ->label('Nổi bật')
                    ->default(false),
                    
                Forms\Components\DateTimePicker::make('published_at')
                    ->label('Thời gian xuất bản')
                    ->default(now()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Ảnh')
                    ->size(40)
                    ->circular(),
                    
                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Tác giả')
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Xuất bản')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                    
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Nổi bật')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),
                    
                Tables\Columns\TextColumn::make('view_count')
                    ->label('Lượt xem')
                    ->numeric()
                    ->badge()
                    ->color('success'),
                    
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Ngày xuất bản')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Trạng thái xuất bản'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Tin nổi bật'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tạo tin tức mới')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['category_id'] = $this->ownerRecord->id;
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Sửa'),
                Tables\Actions\DeleteAction::make()
                    ->label('Xóa'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Xóa các mục đã chọn'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
