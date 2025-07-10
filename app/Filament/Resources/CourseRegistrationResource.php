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
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'pending' => 'Đang chờ',
                        'approved' => 'Đã phê duyệt',
                        'rejected' => 'Đã từ chối',
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Người dùng')
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
                        'approved' => 'Đã phê duyệt',
                        'rejected' => 'Đã từ chối',
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
