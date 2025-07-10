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
    public static function getModelLabel(): string
    {
        return __('Đăng ký khóa học');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Đăng ký khóa học');
    }
    public static function getNavigationLabel(): string
    {
        return __('Quản lý đăng ký khóa học');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\select::make('user_id')
                    ->label(__('Người dùng'))
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\select::make('course_id')
                    ->label(__('Khóa học'))
                    ->relationship('course', 'title')
                    ->required(),
                Forms\Components\DateTimePicker::make('registration_date')
                    ->label(__('Ngày đăng ký'))
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label(__('Trạng thái'))
                    ->options([
                        'pending' => __('Đang chờ'),
                        'approved' => __('Đã phê duyệt'),
                        'rejected' => __('Đã từ chối'),
                    ])
                    ->required(),
                Forms\Components\Select::make('payment_status')
                    ->label(__('Trạng thái thanh toán'))
                    ->options([
                        'paid' => __('Đã thanh toán'),
                        'unpaid' => __('Chưa thanh toán'),
                        'refunded' => __('Đã hoàn tiền'),
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Người dùng'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('course.title')
                    ->label(__('Khóa học'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration_date')
                    ->label(__('Ngày đăng ký'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('Trạng thái'))
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => __('Đang chờ'),
                        'approved' => __('Đã phê duyệt'),
                        'rejected' => __('Đã từ chối'),
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label(__('Trạng thái thanh toán'))
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'paid' => __('Đã thanh toán'),
                        'unpaid' => __('Chưa thanh toán'),
                        'refunded' => __('Đã hoàn tiền'),
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Ngày tạo'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Ngày cập nhật'))
                    ->dateTime()
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
