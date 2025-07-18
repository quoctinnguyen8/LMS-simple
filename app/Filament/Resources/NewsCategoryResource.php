<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsCategoryResource\Pages;
use App\Filament\Resources\NewsCategoryResource\RelationManagers;
use App\Models\NewsCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class NewsCategoryResource extends Resource
{
    protected static ?string $model = NewsCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    
    protected static ?string $navigationLabel = 'Danh mục tin tức';
    
    protected static ?string $modelLabel = 'Danh mục tin tức';
    
    protected static ?string $pluralModelLabel = 'Danh mục tin tức';
    
    protected static ?string $navigationGroup = 'Quản lý tin tức';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin danh mục')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên danh mục')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                        
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug (URL thân thiện)')
                            ->required()
                            ->maxLength(255)
                            ->unique(NewsCategory::class, 'slug', ignoreRecord: true)
                            ->rules(['regex:/^[a-z0-9\-]+$/'])
                            ->helperText('Chỉ được sử dụng chữ thường, số và dấu gạch ngang'),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->description('Chỉ có thể xóa các danh mục không có tin tức liên kết.')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên danh mục')
                    ->description(fn (NewsCategory $record) => $record->slug)
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('news_count')
                    ->label('Số tin tức')
                    ->counts('news')
                    ->badge()
                    ->color('success'),
                    
                Tables\Columns\TextColumn::make('description')
                    ->label('Mô tả')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Cập nhật cuối')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Sửa'),
                Tables\Actions\DeleteAction::make()
                    ->label('Xóa')
                    ->requiresConfirmation()
                    ->modalHeading('Xác nhận xóa danh mục')
                    ->modalDescription(function (NewsCategory $record) {
                        $newsCount = $record->news()->count();
                        if ($newsCount > 0) {
                            return "Không thể xóa danh mục '{$record->name}' vì nó có {$newsCount} tin tức liên kết. Vui lòng xóa các tin tức này trước.";
                        }
                        return "Bạn có chắc chắn muốn xóa danh mục '{$record->name}'?";
                    })
                    ->disabled(fn (NewsCategory $record) => $record->news()->count() > 0)
                    ->color('danger'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Xóa các mục đã chọn')
                        ->requiresConfirmation()
                        ->modalHeading('Xác nhận xóa các danh mục')
                        ->modalDescription('Bạn có chắc chắn muốn xóa các danh mục đã chọn?'
                                        . ' Các danh mục này sẽ không thể phục hồi sau khi xóa.'
                                        . ' Lưu ý: Chỉ có thể xóa các danh mục không có tin tức liên kết.')
                        ->action(function (array $records) {
                            foreach ($records as $record) {
                                if ($record->news()->count() > 0) {
                                    Notification::make()
                                        ->title("Không thể xóa danh mục '{$record->name}' vì nó có tin tức liên kết.")
                                        ->danger()
                                        ->send();
                                    continue;
                                }
                                $record->delete();
                            }
                            Notification::make()
                                ->title("Đã xóa " . count($records) . " danh mục")
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tạo danh mục đầu tiên'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\NewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsCategories::route('/'),
            'create' => Pages\CreateNewsCategory::route('/create'),
            'edit' => Pages\EditNewsCategory::route('/{record}/edit'),
        ];
    }
}
