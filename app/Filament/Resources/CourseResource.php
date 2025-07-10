<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Khóa học';

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
        return 'Thông tin khóa học';
    }
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin cơ bản')
                    ->description('Nhập thông tin cơ bản của khóa học')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->label('Tên khóa học')
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null)
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->placeholder('URL thân thiện, ví dụ: khoa-hoc-tieng-trung')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        Forms\Components\Select::make('category_id')
                            ->label('Danh mục khóa học')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\Select::make('status')
                            ->required()
                            ->label('Trạng thái')
                            ->options([
                                'draft' => 'Nháp',
                                'published' => 'Đã xuất bản',
                                'archived' => 'Đã lưu trữ',
                            ])
                            ->default('draft')
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('price')
                            ->label('Giá khóa học')
                            ->required()
                            ->numeric(12,0)
                            ->default(null)
                            ->prefix('₫')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters([',', '.'])
                            ->dehydrateStateUsing(fn ($state) => (int) str_replace([','], '', $state))
                            ->columnSpan(2),
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Ngày bắt đầu')
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->required()
                            ->minDate(now())
                            ->columnSpan(1),
                        Forms\Components\DatePicker::make('end_registration_date')
                            ->label('Ngày kết thúc đăng ký')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->minDate(now())
                            ->columnSpan(1),
                    ])
                    ->columns(4)
                    ->collapsible(),

                Forms\Components\Section::make('Nội dung khóa học')
                    ->description('Mô tả và nội dung chi tiết của khóa học')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả ngắn về khóa học')
                            ->maxLength(1000)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('content')
                            ->label('Nội dung khóa học')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Hình ảnh')
                            ->disk('public')
                            ->image(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Tên khóa học')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Hình ảnh'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Giá')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state) . ' ₫' : '-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Danh mục')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_registration_date')
                    ->date('d/m/Y')
                    ->label('Ngày k.thúc đăng ký')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date('d/m/Y')
                    ->label('Ngày bắt đầu')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Nháp',
                        'published' => 'Hiển thị',
                        'archived' => 'Lưu trữ',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Danh mục')
                    ->multiple()
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
            
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Nháp',
                        'published' => 'Hiển thị',
                        'archived' => 'Lưu trữ',
                    ])
                    ->default(null),
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
