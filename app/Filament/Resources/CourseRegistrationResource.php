<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseRegistrationResource\Pages;
use App\Filament\Resources\CourseRegistrationResource\RelationManagers;
use App\Models\CourseRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\CourseRegistrationNotification;

class CourseRegistrationResource extends Resource
{
    protected static ?string $model = CourseRegistration::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Khóa học';


    public static function getModelLabel(): string
    {
        return 'đăng ký';
    }
    public static function getPluralModelLabel(): string
    {
        return 'Đăng ký khóa học';
    }
    public static function getNavigationLabel(): string
    {
        return 'Thông tin đăng ký';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Thông tin học viên')
                    ->description('Thông tin cá nhân của học viên đăng ký khóa học')
                    ->schema([
                        Forms\Components\select::make('course_id')
                            ->label('Khóa học')
                            // chỉ chọn các khóa học có trạng thái là published và có ngày bắt đầu chưa quá 30 ngày tính từ hôm nay
                            ->relationship(
                                'course',
                                'title',
                                fn(Builder $query) => $query->where('status', 'published')
                                    ->where(
                                        'start_date',
                                        '>=',
                                        now()->subDays(30)
                                    )
                            )
                            ->helperText('Chỉ hiển thị các khóa học đã được xuất bản và có ngày bắt đầu từ ' . now()->subDays(30)->format('d/m/Y'))
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->optionsLimit(15)
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('student_name')
                            ->label('Họ và tên học viên')
                            ->required()
                            ->maxLength(100)
                            ->columnSpan([
                                'default' => 2,
                                'sm' => 2,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),
                        Forms\Components\TextInput::make('student_phone')
                            ->label('Số điện thoại')
                            ->rules(['regex:/^0[0-9]{9}$/'])
                            ->required()
                            ->placeholder('Bắt đầu bằng 0, 10 chữ số')
                            ->maxLength(10)
                            ->columnSpan([
                                'default' => 2,
                                'sm' => 2,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),
                        Forms\Components\TextInput::make('student_email')
                            ->label('Email học viên')
                            ->nullable()
                            ->email()
                            ->maxLength(200)
                            ->columnSpan([
                                'default' => 2,
                                'sm' => 2,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),
                        Forms\Components\TextInput::make('student_address')
                            ->label('Địa chỉ')
                            ->maxLength(255)
                            ->default(null)
                            ->columnSpan([
                                'default' => 2,
                                'sm' => 2,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),
                        Forms\Components\DatePicker::make('student_birth_date')
                            ->label('Ngày sinh')
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->placeholder('dd/MM/yyyy')
                            ->minDate(now()->subYears(100))
                            ->maxDate(now())
                            ->columnSpan([
                                'default' => 2,
                                'sm' => 2,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),
                        Forms\Components\Select::make('student_gender')
                            ->label('Giới tính')
                            ->native(false)
                            ->options([
                                'male' => 'Nam',
                                'female' => 'Nữ',
                                'other' => 'Khác',
                            ])
                            ->columnSpan([
                                'default' => 2,
                                'sm' => 2,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('Trạng thái')
                    ->description('Trạng thái đăng ký và thanh toán')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Trạng thái đăng ký')
                            ->native(false)
                            ->options([
                                'pending' => 'Đang chờ',
                                'confirmed' => 'Đã xác nhận',
                                'canceled' => 'Đã hủy',
                                'completed' => 'Đã hoàn thành',
                            ])
                            ->required(),
                        Forms\Components\Select::make('payment_status')
                            ->label('Trạng thái thanh toán')
                            ->native(false)
                            ->options([
                                'paid' => 'Đã thanh toán',
                                'unpaid' => 'Chưa thanh toán',
                                'refunded' => 'Đã hoàn tiền'
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('actual_price')
                            ->label('Đã thu')
                            ->integer()
                            ->default(null)
                            ->prefix('₫')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters([',', '.'])
                            ->dehydrateStateUsing(fn($state) => (int) str_replace([','], '', $state))
                            ->dehydrated(fn($context) => $context !== 'create'), // Chỉ lưu khi edit
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
                Tables\Columns\TextColumn::make('student_name')
                    ->label('Tên học viên')
                    ->description(fn(CourseRegistration $record) => $record->student_phone)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Khóa học')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration_date')
                    ->label('Ngày đăng ký')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Đang chờ',
                        'confirmed' => 'Đã xác nhận',
                        'canceled' => 'Đã hủy',
                        'completed' => 'Đã hoàn thành',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Trạng thái thanh toán')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'paid' => 'Đã thanh toán',
                        'unpaid' => 'Chưa thanh toán',
                        'refunded' => 'Đã hoàn tiền',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'warning',
                        'refunded' => 'danger',
                        default => 'gray'
                    }),
                Tables\Columns\TextColumn::make('actual_price')
                    ->label('Đã thu')
                    ->formatStateUsing(fn($state) => $state ? number_format($state) . ' ₫' : '-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                // lọc theo khóa học
                Tables\Filters\SelectFilter::make('course_id')
                    ->label('Khóa học')
                    ->relationship('course', 'title')
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->optionsLimit(15),
                // lọc theo trạng thái thanh toán
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Trạng thái thanh toán')
                    ->options([
                        'paid' => 'Đã thanh toán',
                        'unpaid' => 'Chưa thanh toán',
                        'refunded' => 'Đã hoàn tiền'
                    ])
                    ->multiple()
                    ->native(false),
                // lọc theo trạng thái đăng ký
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái đăng ký')
                    ->options([
                        'pending' => 'Đang chờ',
                        'confirmed' => 'Đã xác nhận',
                        'canceled' => 'Đã hủy',
                        'completed' => 'Đã hoàn thành',
                    ])
                    ->multiple()
                    ->native(false),

            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),

                    Tables\Actions\EditAction::make(),

                    // Action xác nhận đăng ký
                    Tables\Actions\Action::make('confirm')
                        ->label('Xác nhận đăng ký')
                        ->icon('heroicon-o-check-circle')
                        ->requiresConfirmation()
                        ->modalHeading('Xác nhận đăng ký khóa học')
                        ->modalDescription('Bạn có chắc chắn muốn xác nhận đăng ký này?')
                        ->action(function (CourseRegistration $record) {
                            $oldStatus = $record->status;
                            $record->update(['status' => 'confirmed']);
                            if ($record->student_email) {
                                Mail::to($record->student_email)->send(new CourseRegistrationNotification($record));
                            }
                            Log::info('Course registration confirmed', [
                                'registration_id' => $record->id,
                                'course_id' => $record->course_id,
                                'course_title' => $record->course->title ?? 'Unknown',
                                'student_name' => $record->student_name,
                                'student_phone' => $record->student_phone,
                                'student_email' => $record->student_email,
                                'old_status' => $oldStatus,
                                'new_status' => 'confirmed',
                                'confirmed_by_user_id' => Auth::id(),
                                'confirmed_by_user_name' => Auth::user()->name ?? 'Unknown',
                                'ip_address' => request()->ip(),
                                'user_agent' => request()->userAgent(),
                            ]);
                        })
                        ->visible(fn(CourseRegistration $record) => $record->status === 'pending'),

                    // Action hủy đăng ký
                    Tables\Actions\Action::make('cancel')
                        ->label('Hủy đăng ký')
                        ->icon('heroicon-o-x-circle')
                        ->requiresConfirmation()
                        ->modalHeading('Hủy đăng ký khóa học')
                        ->modalDescription('Bạn có chắc chắn muốn hủy đăng ký này? Thao tác này không thể hoàn tác.')
                        ->action(function (CourseRegistration $record) {
                            $oldStatus = $record->status;
                            $record->update(['status' => 'canceled']);
                            if ($record->student_email) {
                                Mail::to($record->student_email)->send(new CourseRegistrationNotification($record));
                            }
                            Log::info('Course registration cancelled', [
                                'registration_id' => $record->id,
                                'course_id' => $record->course_id,
                                'course_title' => $record->course->title ?? 'Unknown',
                                'student_name' => $record->student_name,
                                'student_phone' => $record->student_phone,
                                'student_email' => $record->student_email,
                                'old_status' => $oldStatus,
                                'new_status' => 'canceled',
                                'cancelled_by_user_id' => Auth::id(),
                                'cancelled_by_user_name' => Auth::user()->name ?? 'Unknown',
                                'ip_address' => request()->ip(),
                                'user_agent' => request()->userAgent(),
                            ]);
                        })
                        ->disabled(fn(CourseRegistration $record) => in_array($record->status, ['pending', 'confirmed'])),

                    // Action đánh dấu hoàn thành
                    Tables\Actions\Action::make('complete')
                        ->label('Đánh dấu hoàn thành')
                        ->icon('heroicon-o-academic-cap')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Hoàn thành khóa học')
                        ->modalDescription('Đánh dấu học viên đã hoàn thành khóa học này?')
                        ->action(function (CourseRegistration $record) {
                            $oldStatus = $record->status;
                            $record->update(['status' => 'completed']);
                            if ($record->student_email) {
                                Mail::to($record->student_email)->send(new CourseRegistrationNotification($record, 'completed'));
                            }
                            Log::info('Course registration completed', [
                                'registration_id' => $record->id,
                                'course_id' => $record->course_id,
                                'course_title' => $record->course->title ?? 'Unknown',
                                'student_name' => $record->student_name,
                                'student_phone' => $record->student_phone,
                                'student_email' => $record->student_email,
                                'old_status' => $oldStatus,
                                'new_status' => 'completed',
                                'completed_by_user_id' => Auth::id(),
                                'completed_by_user_name' => Auth::user()->name ?? 'Unknown',
                                'ip_address' => request()->ip(),
                                'user_agent' => request()->userAgent(),
                            ]);
                        })
                        ->disabled(fn(CourseRegistration $record) => $record->status === 'confirmed'),

                    // Action cập nhật thanh toán
                    Tables\Actions\Action::make('update_payment')
                        ->label('Cập nhật thanh toán')
                        ->icon('heroicon-o-currency-dollar')
                        ->form([
                            Forms\Components\Select::make('payment_status')
                                ->label('Trạng thái thanh toán')
                                ->options([
                                    'paid' => 'Đã thanh toán',
                                    'unpaid' => 'Chưa thanh toán',
                                    'refunded' => 'Đã hoàn tiền'
                                ])
                                ->required()
                                ->native(false),
                            Forms\Components\TextInput::make('actual_price')
                                ->label('Số tiền thực thu')
                                ->integer()
                                ->prefix('₫')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters([',', '.'])
                                ->dehydrateStateUsing(fn($state) => (int) str_replace([','], '', $state))
                        ])
                        ->fillForm(fn(CourseRegistration $record): array => [
                            'payment_status' => $record->payment_status,
                            'actual_price' => $record->actual_price,
                        ])
                        ->action(function (CourseRegistration $record, array $data) {
                            $oldPaymentStatus = $record->payment_status;
                            $oldActualPrice = $record->actual_price;

                            $record->update([
                                'payment_status' => $data['payment_status']
                            ]);
                            if ($record->student_email) {
                                Mail::to($record->student_email)->send(new CourseRegistrationNotification($record));
                            }
                            Log::info('Course registration payment updated', [
                                'registration_id' => $record->id,
                                'course_id' => $record->course_id,
                                'course_title' => $record->course->title ?? 'Unknown',
                                'student_name' => $record->student_name,
                                'student_phone' => $record->student_phone,
                                'old_payment_status' => $oldPaymentStatus,
                                'new_payment_status' => $data['payment_status'],
                                'old_actual_price' => $oldActualPrice,
                                'new_actual_price' => $data['actual_price'],
                                'updated_by_user_id' => Auth::id(),
                                'updated_by_user_name' => Auth::user()->name ?? 'Unknown',
                                'ip_address' => request()->ip(),
                                'user_agent' => request()->userAgent(),
                            ]);
                        })
                        ->modalWidth('md'),

                    Tables\Actions\DeleteAction::make()
                        ->before(function (CourseRegistration $record) {
                            // Log trước khi xóa để giữ thông tin
                            Log::info('Course registration deleted', [
                                'registration_id' => $record->id,
                                'course_id' => $record->course_id,
                                'course_title' => $record->course->title ?? 'Unknown',
                                'student_name' => $record->student_name,
                                'student_phone' => $record->student_phone,
                                'student_email' => $record->student_email,
                                'student_address' => $record->student_address,
                                'status' => $record->status,
                                'payment_status' => $record->payment_status,
                                'actual_price' => $record->actual_price,
                                'registration_date' => $record->registration_date,
                                'deleted_by_user_id' => Auth::id(),
                                'deleted_by_user_name' => Auth::user()->name ?? 'Unknown',
                                'ip_address' => request()->ip(),
                                'user_agent' => request()->userAgent(),
                            ]);
                        })
                        ->disabled(fn(CourseRegistration $record) => Auth::user()->role !== 'admin')
                ])
                    ->icon('heroicon-o-ellipsis-vertical')
                    ->color('gray')
                    ->tooltip('Thao tác')
                    ->iconButton()
                    ->extraAttributes(['class' => 'border']),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Bulk xác nhận đăng ký
                    Tables\Actions\BulkAction::make('bulk_confirm')
                        ->label('Xác nhận hàng loạt')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Xác nhận đăng ký hàng loạt')
                        ->modalDescription('Chỉ xác nhận các đăng ký có trạng thái "Đang chờ". Các đăng ký khác sẽ được bỏ qua.')
                        ->action(function ($records) {
                            $confirmedCount = 0;
                            $confirmedRegistrations = [];

                            foreach ($records as $record) {
                                if ($record->status === 'pending') {
                                    $record->update(['status' => 'confirmed']);
                                    $confirmedCount++;
                                    $confirmedRegistrations[] = [
                                        'registration_id' => $record->id,
                                        'student_name' => $record->student_name,
                                        'course_title' => $record->course->title ?? 'Unknown',
                                    ];
                                    if ($record->student_email) {
                                        Mail::to($record->student_email)->send(new CourseRegistrationNotification($record));
                                    }
                                }
                            }

                            Log::info('Bulk confirm course registrations completed', [
                                'confirmed_count' => $confirmedCount,
                                'total_selected' => count($records),
                                'confirmed_registrations' => $confirmedRegistrations,
                                'confirmed_by_user_id' => Auth::id(),
                                'confirmed_by_user_name' => Auth::user()->name ?? 'Unknown',
                                'ip_address' => request()->ip(),
                                'user_agent' => request()->userAgent(),
                            ]);

                            \Filament\Notifications\Notification::make()
                                ->title('Hoàn thành xác nhận hàng loạt')
                                ->body("Đã xác nhận {$confirmedCount} đăng ký.")
                                ->success()
                                ->send();
                        })
                        ->modalWidth('md')
                        ->deselectRecordsAfterCompletion(),

                    // Bulk hủy đăng ký
                    Tables\Actions\BulkAction::make('bulk_cancel')
                        ->label('Hủy hàng loạt')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Hủy đăng ký hàng loạt')
                        ->modalDescription('Chỉ hủy các đăng ký có trạng thái "Đang chờ" hoặc "Đã xác nhận". Các đăng ký khác sẽ được bỏ qua.')
                        ->action(function ($records) {
                            $cancelledCount = 0;
                            $cancelledRegistrations = [];

                            foreach ($records as $record) {
                                if (in_array($record->status, ['pending', 'confirmed'])) {
                                    $record->update(['status' => 'canceled']);
                                    $cancelledCount++;
                                    $cancelledRegistrations[] = [
                                        'registration_id' => $record->id,
                                        'student_name' => $record->student_name,
                                        'course_title' => $record->course->title ?? 'Unknown',
                                    ];
                                    if ($record->student_email) {
                                        Mail::to($record->student_email)->send(new CourseRegistrationNotification($record));
                                    }
                                }
                            }

                            Log::info('Bulk cancel course registrations completed', [
                                'cancelled_count' => $cancelledCount,
                                'total_selected' => count($records),
                                'cancelled_registrations' => $cancelledRegistrations,
                                'cancelled_by_user_id' => Auth::id(),
                                'cancelled_by_user_name' => Auth::user()->name ?? 'Unknown',
                                'ip_address' => request()->ip(),
                                'user_agent' => request()->userAgent(),
                            ]);

                            \Filament\Notifications\Notification::make()
                                ->title('Hoàn thành hủy hàng loạt')
                                ->body("Đã hủy {$cancelledCount} đăng ký.")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            $deletedRegistrations = [];
                            foreach ($records as $record) {
                                $deletedRegistrations[] = [
                                    'registration_id' => $record->id,
                                    'student_name' => $record->student_name,
                                    'student_phone' => $record->student_phone,
                                    'student_email' => $record->student_email,
                                    'course_title' => $record->course->title ?? 'Unknown',
                                    'status' => $record->status,
                                    'payment_status' => $record->payment_status,
                                    'actual_price' => $record->actual_price,
                                ];
                            }

                            Log::info('Bulk delete course registrations', [
                                'deleted_count' => count($records),
                                'deleted_registrations' => $deletedRegistrations,
                                'deleted_by_user_id' => Auth::id(),
                                'deleted_by_user_name' => Auth::user()->name ?? 'Unknown',
                                'ip_address' => request()->ip(),
                                'user_agent' => request()->userAgent(),
                            ]);
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
            'index' => Pages\ListCourseRegistrations::route('/'),
            'create' => Pages\CreateCourseRegistration::route('/create'),
            'edit' => Pages\EditCourseRegistration::route('/{record}/edit'),
        ];
    }
}
