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
                            ->relationship('course', 'title',
                                fn (Builder $query) => $query->where('status', 'published')
                                    ->where('start_date', '>=', now()->subDays(30)
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
                            ->maxLength(100),
                        Forms\Components\TextInput::make('student_phone')
                            ->label('Số điện thoại')
                            ->rules(['regex:/^0[0-9]{9}$/'])
                            ->required()
                            ->placeholder('Bắt đầu bằng 0, 10 chữ số')
                            ->maxLength(10),
                        Forms\Components\TextInput::make('student_email')
                            ->label('Email học viên')
                            ->nullable()
                            ->email()
                            ->maxLength(200),
                        Forms\Components\TextInput::make('student_address')
                            ->label('Địa chỉ')
                            ->maxLength(255)
                            ->default(null),
                        Forms\Components\DatePicker::make('student_birth_date')
                            ->label('Ngày sinh')
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->placeholder('dd/MM/yyyy')
                            ->minDate(now()->subYears(100))
                            ->maxDate(now()),
                        Forms\Components\Select::make('student_gender')
                            ->label('Giới tính')
                            ->native(false)
                            ->options([
                                'male' => 'Nam',
                                'female' => 'Nữ',
                                'other' => 'Khác',
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
                                'pending' => 'Chờ thanh toán',
                                'cancelled' => 'Đã hủy',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('actual_price')
                            ->label('Đã thu')
                            ->integer()
                            ->default(null)
                            ->prefix('₫')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters([',', '.'])
                            ->dehydrateStateUsing(fn ($state) => (int) str_replace([','], '', $state))
                            ->disabled(fn ($context) => $context === 'create') // Chỉ disable khi tạo mới
                            ->dehydrated(fn ($context) => $context !== 'create'), // Chỉ lưu khi edit
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
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Đang chờ',
                        'confirmed' => 'Đã xác nhận',
                        'canceled' => 'Đã hủy',
                        'completed' => 'Đã hoàn thành',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Trạng thái thanh toán')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'paid' => 'Đã thanh toán',
                        'pending' => 'Chờ thanh toán',
                        'cancelled' => 'Đã hủy',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('actual_price')
                    ->label('Đã thu')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state) . ' ₫' : '-')
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
                        'pending' => 'Chờ thanh toán',
                        'cancelled' => 'Đã hủy',
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
            'index' => Pages\ListCourseRegistrations::route('/'),
            'create' => Pages\CreateCourseRegistration::route('/create'),
            'edit' => Pages\EditCourseRegistration::route('/{record}/edit'),
        ];
    }
}
