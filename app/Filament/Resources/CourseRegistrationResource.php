<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseRegistrationResource\Pages;
use App\Filament\Resources\CourseRegistrationResource\RelationManagers;
use App\Models\CourseRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseRegistrationResource extends Resource
{
    protected static ?string $model = CourseRegistration::class;

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
                Forms\Components\Section::make('Thông tin đăng ký')
                    ->description('Nhập thông tin đăng ký khóa học')
                    ->schema([
                        Forms\Components\select::make('user_id')
                            ->label('Người dùng')
                            ->relationship('user', 'name')
                            ->required(),
                        Forms\Components\select::make('course_id')
                            ->label('Khóa học')
                            ->relationship('course', 'title')
                            ->required(),
                        Forms\Components\DateTimePicker::make('registration_date')
                            ->label('Ngày đăng ký')
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->required(),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Forms\Components\Section::make('Trạng thái')
                    ->description('Trạng thái đăng ký và thanh toán')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'pending' => 'Đang chờ',
                                'confirmed' => 'Đã xác nhận',
                                'canceled' => 'Đã hủy',
                                'completed' => 'Đã hoàn thành',
                            ])
                            ->required(),
                        Forms\Components\Select::make('payment_status')
                            ->label('Trạng thái thanh toán')
                            ->options([
                                'paid' => 'Đã thanh toán',
                                'unpaid' => 'Chưa thanh toán',
                                'refunded' => 'Đã hoàn tiền',
                            ])
                            ->required(),
                    ])
                    ->columns(2)
                    ->collapsible(),
                Forms\Components\Section::make('Thông tin học viên')
                    ->description('Thông tin cá nhân của học viên đăng ký khóa học')
                    ->schema([
                        Forms\Components\TextInput::make('student_name')
                            ->label('Họ và tên học viên')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('student_email')
                            ->label('Email học viên')
                            ->required()
                            ->email()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('student_phone')
                            ->label('Số điện thoại')
                            ->rules(['regex:/^0[0-9]{9}$/'])
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->tel(),
                        Forms\Components\TextInput::make('student_address')
                            ->label('Địa chỉ')
                            ->maxLength(255)
                            ->default(null),
                        Forms\Components\DatePicker::make('student_birth_date')
                            ->label('Ngày sinh')
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->required()
                            ->minDate(now()->subYears(100))
                            ->maxDate(now()),
                        Forms\Components\Select::make('student_gender')
                            ->label('Giới tính')
                            ->options([
                                'male' => 'Nam',
                                'female' => 'Nữ',
                                'other' => 'Khác',
                            ])
                            ->default('other'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                        'unpaid' => 'Chưa thanh toán',
                        'refunded' => 'Đã hoàn tiền',
                        default => $state,
                    }),
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
            'index' => Pages\ListCourseRegistrations::route('/'),
            'create' => Pages\CreateCourseRegistration::route('/create'),
            'edit' => Pages\EditCourseRegistration::route('/{record}/edit'),
        ];
    }
}
