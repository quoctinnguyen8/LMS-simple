<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Filament\Resources\NewsResource\RelationManagers;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    
    protected static ?string $navigationLabel = 'Tin tức';
    
    protected static ?string $modelLabel = 'Tin tức';
    
    protected static ?string $pluralModelLabel = 'Tin tức';
    
    protected static ?string $navigationGroup = 'Quản lý tin tức';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Nội dung')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Tiêu đề')
                            ->required()
                            ->maxLength(500)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                $set('slug', Str::slug($state));
                                $set('seo_title', $state);
                            }),
                            
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug (URL thân thiện)')
                            ->required()
                            ->maxLength(500)    
                            ->rules(['regex:/^[a-z0-9\-]+$/']),
                            
                        Forms\Components\Select::make('category_id')
                            ->label('Danh mục')
                            ->relationship('news_category', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Tên danh mục')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->unique(NewsCategory::class, 'slug'),
                                Forms\Components\Textarea::make('description')
                                    ->label('Mô tả')
                                    ->rows(3),
                            ]),
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Ảnh bìa')
                            ->image()
                            ->required()
                            ->maxSize(2048) // 2MB
                            ->acceptedFileTypes(['image/*'])
                            ->directory('news')
                            ->visibility('public')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ]),

                        Forms\Components\Textarea::make('summary')
                            ->label('Tóm tắt')
                            ->rows(3)
                            ->maxLength(1000)
                            ->helperText('Tóm tắt ngắn gọn về nội dung tin tức'),
                            
                        Forms\Components\RichEditor::make('content')
                            ->label('Nội dung chi tiết')
                            ->required()
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo'
                            ]),
                    ]),
                    
                Forms\Components\Section::make('Cài đặt xuất bản')
                    ->schema([
                        Forms\Components\Toggle::make('is_published')
                            ->label('Xuất bản')
                            ->default(true)
                            ->helperText('Bật để hiển thị tin tức này công khai')
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $set('published_at', now());
                                } else {
                                    $set('published_at', null);
                                }
                            }),
                            
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Thời gian xuất bản')
                            ->default(now())
                            ->native(false)
                            ->displayFormat('d/m/Y H:i')
                            ,
                            
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Tin tức nổi bật')
                            ->helperText('Tin tức nổi bật sẽ được ưu tiên hiển thị hơn các tin khác'),
                            
                        Forms\Components\TextInput::make('view_count')
                            ->label('Lượt xem')
                            ->numeric()
                            ->default(0)
                            ->readOnly(),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('SEO & Meta Data')
                    ->schema([
                        Forms\Components\TextInput::make('seo_image')
                            ->label('Ảnh SEO')
                            ->maxLength(500)
                            ->helperText('Ảnh được sử dụng khi chia sẻ trên mạng xã hội, để trống sẽ dùng ảnh bìa.'),

                        Forms\Components\TextInput::make('seo_title')
                            ->label('Tiêu đề SEO')
                            ->maxLength(500)
                            ->helperText('Tiêu đề tối ưu cho công cụ tìm kiếm'),
                            
                        Forms\Components\Textarea::make('seo_description')
                            ->label('Mô tả SEO')
                            ->maxLength(2000)
                            ->rows(3)
                            ->helperText('Mô tả ngắn gọn cho công cụ tìm kiếm'),
                    ])
                    ->columns(1)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Ảnh')
                    ->size(50)
                    ->circular(),
                    
                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->description(fn (News $record) => $record->slug)
                    ->icon(function (News $record) {
                        return $record->is_featured ? 'heroicon-o-star' : 'heroicon-o-newspaper';
                    })
                    ->iconColor(fn (News $record) => $record->is_featured ? 'primary' : 'gray')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->weight(FontWeight::Medium),
                    
                Tables\Columns\TextColumn::make('news_category.name')
                    ->label('Danh mục')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Tác giả')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Xuất bản')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                    
                Tables\Columns\TextColumn::make('view_count')
                    ->label('Lượt xem')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                    
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Ngày xuất bản')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
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
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Danh mục')
                    ->relationship('news_category', 'name')
                    ->searchable()
                    ->preload(),
                    
                Tables\Filters\SelectFilter::make('author_id')
                    ->label('Tác giả')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                    
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Trạng thái xuất bản')
                    ->boolean()
                    ->trueLabel('Đã xuất bản')
                    ->falseLabel('Chưa xuất bản')
                    ->native(false),
                    
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Tin nổi bật')
                    ->boolean()
                    ->trueLabel('Nổi bật')
                    ->falseLabel('Thường')
                    ->native(false),
                    
                Tables\Filters\Filter::make('published_date')
                    ->form([
                        Forms\Components\DatePicker::make('published_from')
                            ->label('Từ ngày'),
                        Forms\Components\DatePicker::make('published_until')
                            ->label('Đến ngày'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['published_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '>=', $date),
                            )
                            ->when(
                                $data['published_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                // group actions
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->label('Sửa'),
                    Tables\Actions\Action::make('toggle_published')
                        ->label(fn (News $record): string => $record->is_published ? 'Ẩn tin tức' : 'Xuất bản ngay')
                        ->icon(fn (News $record): string => $record->is_published ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                        ->action(function (News $record): void {
                            $record->update([
                                'is_published' => !$record->is_published,
                                'published_at' => !$record->is_published ? now() : null,
                            ]);
                        }),
                    // nổi bật/ẩn tin tức
                    Tables\Actions\Action::make('toggle_featured')
                        ->label(fn (News $record): string => $record->is_featured ? 'Ẩn tin tức nổi bật' : 'Đặt là nổi bật')
                        ->icon(fn (News $record): string => $record->is_featured ? 'heroicon-o-star' : 'heroicon-o-star')
                        ->action(function (News $record): void {
                            $record->update([
                                'is_featured' => !$record->is_featured,
                            ]);
                        }),
                    Tables\Actions\DeleteAction::make()
                        ->label('Xóa'),
                ])
                ->iconButton()
                ->icon('heroicon-o-ellipsis-vertical')
                ->color('gray')
                ->tooltip('Thao tác')
                ->extraAttributes(['class' => 'border']),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Xóa các mục đã chọn'),
                    Tables\Actions\BulkAction::make('toggle_published')
                        ->label('Thay đổi trạng thái xuất bản')
                        ->icon('heroicon-o-eye')
                        ->modalWidth('sm')
                        ->form([
                            Forms\Components\Select::make('is_published')
                                ->label('Trạng thái')
                                ->options([
                                    1 => 'Xuất bản',
                                    0 => 'Ẩn',
                                ])
                                ->required(),
                        ])
                        ->action(function (array $data, $records): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'is_published' => $data['is_published'],
                                    'published_at' => $data['is_published'] ? ($record->published_at ?? now()) : null,
                                ]);
                            }
                        }),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tạo tin tức đầu tiên'),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    // public static function getWidgets(): array
    // {
    //     return [
    //     ];
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
