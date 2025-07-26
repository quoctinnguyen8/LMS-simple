<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
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
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Hình ảnh')
                            ->disk('public')
                            ->directory('course-images')
                            ->image()
                            ->imageEditor()
                            ->maxSize(2048) // 4MB
                            ->acceptedFileTypes(['image/*'])
                            ->helperText('Kích thước tối đa: 2MB. Định dạng: JPG, PNG, WebP,...')
                            ->columnSpan(4),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->label('Tên khóa học')
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                $set('slug', \Illuminate\Support\Str::slug($state));
                                $set('seo_title', $state);
                            })
                            ->columnSpan([
                                'default' => 4,
                                'sm' => 4,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 2,
                            ]),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->placeholder('URL thân thiện, ví dụ: khoa-hoc-tieng-trung')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan([
                                'default' => 4,
                                'sm' => 4,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 2,
                            ]),
                        Forms\Components\Select::make('category_id')
                            ->label('Danh mục khóa học')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan([
                                'default' => 4,
                                'sm' => 4,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 2,
                            ]),
                        Forms\Components\TextInput::make('max_students')
                            ->label('Số học viên tối đa')
                            ->numeric()
                            ->minValue(1)
                            ->default(10)
                            ->columnSpan([
                                'default' => 4,
                                'sm' => 4,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),
                        Forms\Components\Toggle::make('status')
                            ->label('Xuất bản khóa học')
                            ->default(false)
                            ->inline(false)
                            ->formatStateUsing(fn($state) => $state === 'published')
                            ->dehydrateStateUsing(fn($state) => $state ? 'published' : 'draft')
                            ->columnSpan([
                                'default' => 4,
                                'sm' => 4,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),
                        Forms\Components\TextInput::make('price')
                            ->label('Giá khóa học')
                            ->required()
                            ->integer()
                            ->default(null)
                            ->prefix('₫')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters([',', '.'])
                            ->dehydrateStateUsing(fn($state) => (int) str_replace([','], '', $state))
                            ->columnSpan([
                                'default' => 4,
                                'sm' => 4,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 2,
                            ]),
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Ngày khai giảng')
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->required()
                            ->helperText('Ngày bắt đầu khóa học')
                            ->columnSpan([
                                'default' => 4,
                                'sm' => 4,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),
                        Forms\Components\DatePicker::make('end_registration_date')
                            ->label('Ngày kết thúc đăng ký')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->helperText('Hạn cuối chập nhận đăng ký')
                            ->columnSpan([
                                'default' => 4,
                                'sm' => 4,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),

                        Forms\Components\Toggle::make('allow_overflow')
                            ->label('Cho phép nhận thêm học viên khi đã đủ số lượng tối đa')
                            ->helperText('Hệ thống vẫn cho phép đăng ký từ trang chủ nhưng không vượt quá 100% so với số lượng tối đa. '
                                . 'Thao tác của quản trị viên không bị ảnh hưởng và vẫn có thể đăng ký khóa học thay cho học viên')
                            ->default(true)
                            ->inline(false)
                            ->columnSpan([
                                'default' => 4,
                                'sm' => 4,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 2,
                            ]),
                        Forms\Components\Toggle::make('is_price_visible')
                            ->label('Hiển thị giá')
                            ->default(true)
                            ->inline(false) // Đặt toggle dọc để căn chỉnh với textbox
                            ->columnSpan([
                                'default' => 4,
                                'sm' => 4,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 2,
                            ]),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Nội dung khóa học')
                    ->description('Mô tả và nội dung chi tiết của khóa học')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả ngắn về khóa học')
                            ->maxLength(1000)
                            ->helperText('Mô tả ngắn gọn, hiển thị trên danh sách khóa học')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('content')
                            ->label('Mô tả đầy đủ')
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
                                'undo',
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('course-attachments')
                            ->fileAttachmentsVisibility('public'),
                    ]),
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
            ->recordUrl(null) // Vô hiệu hóa click để chỉnh sửa
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Tên khóa học')
                    ->searchable()
                    ->description(function ($record) {
                        $maxStudents = $record->max_students;
                        if (empty($maxStudents)) {
                            return $record->course_registrations_count . ' học viên đã đăng ký';
                        }
                        if ($record->allow_overflow) {
                            $maxStudents .= "+";
                        }
                        return $record->course_registrations_count . '/' . $maxStudents . ' học viên đã đăng ký';
                    }),
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Hình ảnh')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('price')
                    ->label('Giá')
                    ->formatStateUsing(fn($state) => $state ? number_format($state) . ' ₫' : '-')
                    ->color(fn($record) => $record->is_price_visible ? null : 'gray')
                    ->tooltip(function ($record) {
                        return $record->is_price_visible ? null : 'Giá khóa học này không hiển thị cho người dùng';
                    })
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_price_visible')
                    ->label('Hiển thị giá')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Danh mục')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date('d/m/Y')
                    ->label('Khai giảng')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_registration_date')
                    ->date('d/m/Y')
                    ->label('Ngày k.thúc đăng ký')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->tooltip(function ($record) {
                        if ($record->status === 'draft' || $record->status === 'archived') {
                            return 'Trạng thái này sẽ không hiển thị khóa học cho người dùng';;
                        }
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'draft' => 'Nháp',
                        'published' => 'Hiển thị',
                        'archived' => 'Lưu trữ',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Người tạo')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ->defaultSort('start_date', 'desc')
            ->modifyQueryUsing(fn(Builder $query) => $query->withCount('course_registrations'))
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Danh mục')
                    ->multiple()
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->multiple()
                    ->options([
                        'draft' => 'Nháp',
                        'published' => 'Hiển thị',
                        'archived' => 'Lưu trữ',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['values'])) {
                            // Nếu không có filter nào được chọn, hiển thị mặc định (draft + published)
                            return $query->whereIn('status', ['draft', 'published']);
                        }
                        // Nếu có filter được chọn, áp dụng filter đó (override query mặc định)
                        return $query->whereIn('status', $data['values']);
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('viewStudents')
                        ->label('Danh sách học viên')
                        ->icon('heroicon-o-users')
                        ->modalHeading(fn($record) => 'Danh sách học viên - ' . $record->title)
                        ->modalWidth('7xl')
                        ->modalSubmitAction(false)
                        ->modalCancelAction(false)
                        ->modalContent(function ($record) {
                            $registrations = $record->course_registrations()->with('creator')->get();

                            if ($registrations->isEmpty()) {
                                return view('filament.components.empty-state', [
                                    'message' => 'Chưa có học viên nào đăng ký khóa học này.'
                                ]);
                            }

                            return view('filament.components.students-list', [
                                'registrations' => $registrations,
                                'course' => $record
                            ]);
                        }),
                    Tables\Actions\EditAction::make()
                        ->label('Chỉnh sửa')
                        ->icon('heroicon-o-pencil-square'),
                    Tables\Actions\Action::make('publish')
                        ->label('Xuất bản ngay')
                        ->icon('heroicon-o-rocket-launch')
                        ->visible(fn($record) => $record->status === 'draft')
                        ->action(function ($record) {
                            $record->update(['status' => 'published']);

                            \Filament\Notifications\Notification::make()
                                ->title('Đã xuất bản khóa học')
                                ->body('Khóa học "' . $record->title . '" đã được xuất bản thành công.')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('archive')
                        ->label(fn($record) => $record->status === 'archived' ? 'Bỏ lưu trữ' : 'Lưu trữ')
                        ->icon(fn($record) => $record->status === 'archived' ? 'heroicon-o-archive-box-arrow-down' : 'heroicon-o-archive-box')
                        ->color(fn($record) => $record->status === 'archived' ? 'success' : 'warning')
                        ->requiresConfirmation()
                        ->modalHeading(fn($record) => $record->status === 'archived' ? 'Bỏ lưu trữ khóa học' : 'Lưu trữ khóa học')
                        ->modalDescription(fn($record) => $record->status === 'archived'
                            ? 'Bạn có muốn bỏ lưu trữ khóa học "' . $record->title . '"? Khóa học sẽ chuyển về trạng thái nháp.'
                            : 'Bạn có muốn lưu trữ khóa học "' . $record->title . '"? Khóa học sẽ không hiển thị cho người dùng.')
                        ->modalSubmitActionLabel(fn($record) => $record->status === 'archived' ? 'Bỏ lưu trữ' : 'Lưu trữ')
                        ->action(function ($record) {
                            $newStatus = $record->status === 'archived' ? 'draft' : 'archived';
                            $record->update(['status' => $newStatus]);

                            \Filament\Notifications\Notification::make()
                                ->title($newStatus === 'archived' ? 'Đã lưu trữ khóa học' : 'Đã bỏ lưu trữ khóa học')
                                ->body('Khóa học "' . $record->title . '" đã được ' . ($newStatus === 'archived' ? 'lưu trữ' : 'bỏ lưu trữ') . ' thành công.')
                                ->success()
                                ->send();
                        }),
                    // Xóa
                    Tables\Actions\DeleteAction::make()
                        ->label(function ($record) {
                            return $record->course_registrations_count > 0 ? 'Không thể xóa' : 'Xóa';
                        })
                        ->icon('heroicon-o-trash')
                        ->successNotificationTitle('Khóa học đã được xóa thành công.')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Xóa khóa học')
                        ->modalDescription(function ($record) {
                            $studentsCount = $record->course_registrations_count;

                            if ($studentsCount > 0) {
                                return 'Không thể xóa khóa học này vì đã có ' . $studentsCount . ' học viên đăng ký.';
                            }

                            return 'Bạn có chắc chắn muốn xóa khóa học "' . $record->title . '"? Hành động này không thể hoàn tác.';
                        })
                        ->modalSubmitActionLabel('Xóa')
                        ->modalCancelActionLabel('Hủy')
                        ->disabled(function ($record) {
                            return $record->course_registrations_count > 0;
                        })
                        ->action(function ($record) {
                            $studentsCount = $record->course_registrations_count;
                            if ($studentsCount > 0) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Không thể xóa')
                                    ->body('Khóa học này đã có ' . $studentsCount . ' học viên đăng ký, không thể xóa.')
                                    ->danger()
                                    ->send();
                                return false;
                            }
                            $record->delete();
                        }),
                ])
                    ->icon('heroicon-o-ellipsis-vertical')
                    ->color('gray')
                    ->tooltip('Thao tác')
                    ->extraAttributes(['class' => 'border'])
                    ->iconButton()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('archive')
                        ->label('Lưu trữ')
                        ->icon('heroicon-o-archive-box')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Lưu trữ khóa học')
                        ->modalDescription('Bạn có chắc chắn muốn lưu trữ các khóa học đã chọn? Chúng sẽ không hiển thị cho người dùng.')
                        ->modalSubmitActionLabel('Lưu trữ')
                        ->action(function ($records) {
                            $count = $records->count();
                            $records->each(function ($record) {
                                $record->update(['status' => 'archived']);
                            });

                            \Filament\Notifications\Notification::make()
                                ->title('Đã lưu trữ khóa học')
                                ->body("Đã lưu trữ {$count} khóa học thành công.")
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('unarchive')
                        ->label('Bỏ lưu trữ')
                        ->icon('heroicon-o-archive-box-arrow-down')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Bỏ lưu trữ khóa học')
                        ->modalDescription('Bạn có chắc chắn muốn bỏ lưu trữ các khóa học đã chọn? Chúng sẽ chuyển về trạng thái nháp.')
                        ->modalSubmitActionLabel('Bỏ lưu trữ')
                        ->action(function ($records) {
                            $count = $records->count();
                            $records->each(function ($record) {
                                $record->update(['status' => 'draft']);
                            });

                            \Filament\Notifications\Notification::make()
                                ->title('Đã bỏ lưu trữ khóa học')
                                ->body("Đã bỏ lưu trữ {$count} khóa học thành công. Các khóa học đã chuyển về trạng thái nháp.")
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Xóa khóa học')
                        ->modalDescription('Bạn có chắc chắn muốn xóa các khóa học đã chọn? Chỉ những khóa học chưa có học viên đăng ký mới bị xóa.')
                        ->modalSubmitActionLabel('Xóa')
                        ->action(function ($records) {
                            $deletableRecords = $records->filter(function ($record) {
                                return $record->course_registrations_count == 0;
                            });

                            $protectedRecords = $records->filter(function ($record) {
                                return $record->course_registrations_count > 0;
                            });

                            $deletedCount = $deletableRecords->count();
                            $protectedCount = $protectedRecords->count();

                            // Xóa các khóa học có thể xóa
                            $deletableRecords->each(function ($record) {
                                $record->delete();
                            });

                            // Thông báo kết quả
                            if ($deletedCount > 0 && $protectedCount > 0) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Xóa một phần thành công')
                                    ->body("Đã xóa {$deletedCount} khóa học. {$protectedCount} khóa học không thể xóa vì đã có học viên đăng ký.")
                                    ->warning()
                                    ->send();
                            } elseif ($deletedCount > 0) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Xóa thành công')
                                    ->body("Đã xóa {$deletedCount} khóa học thành công.")
                                    ->success()
                                    ->send();
                            } else {
                                \Filament\Notifications\Notification::make()
                                    ->title('Không thể xóa')
                                    ->body("Tất cả {$protectedCount} khóa học đã chọn đều có học viên đăng ký, không thể xóa.")
                                    ->danger()
                                    ->send();
                            }
                        }),
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
