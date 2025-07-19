<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Mail\PasswordResetNotification;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 1;
    
    protected static ?string $navigationGroup = 'Trang web';

    public static function getModelLabel(): string
    {
        return 'người dùng';
    }
    public static function getPluralModelLabel(): string
    {
        return 'người dùng';
    }
    public static function getNavigationLabel(): string
    {
        return 'Quản lý người dùng';
    }

    // public static function form(Form $form): Form
    // {
    //     return $form;
    // }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null) // Vô hiệu hóa click để chỉnh sửa
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên người dùng')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email người dùng')
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('role')
                    ->label('Vai trò')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'admin' => 'Quản trị viên',
                        'subadmin' => 'Quản trị viên phụ',
                        'user' => 'Người dùng',
                        default => 'Không xác định',
                    })
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // nhóm hành động
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->label('Sửa')
                        ->form([
                            Forms\Components\TextInput::make('name')
                                ->label('Tên người dùng')
                                ->minLength(3)
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('email')
                                ->label('Email người dùng')
                                ->email()
                                ->disabled(fn (User $record) => $record->role != 'admin') // Không cho phép sửa email nếu không phải là quản trị viên
                                ->required()
                                ->maxLength(255),
                            // vai trò
                            Forms\Components\Select::make('role')
                                ->label('Vai trò')
                                ->options([
                                    'admin' => 'Quản trị viên',
                                    'subadmin' => 'Quản trị viên phụ',
                                    'user' => 'Người dùng',
                                ])
                                ->hidden(fn (Forms\Get $get) => Auth::user()?->role !== 'admin') // Chỉ hiển thị cho quản trị viên
                                ->helperText('Quản trị viên có toàn quyền quản lý hệ thống, quản trị viên phụ có quyền hạn hạn chế hơn.')
                        ])
                        ->modalWidth('md')
                        ->icon('heroicon-o-pencil'),
                        Tables\Actions\Action::make('reset_password')
                        ->label('Đặt lại mật khẩu')
                        ->action(function (User $record) {
                            try {
                                // Logic đặt lại mật khẩu
                                // Tạo mã hash md5 và lấy 10 ký tự đầu tiên
                                $hashedPassword = md5(time() . $record->id . $record->email);
                                $newPassword = substr($hashedPassword, 0, 10);
                                // Viết hoa 5 ký tự đầu tiên
                                $newPassword = strtoupper(substr($newPassword, 0, 5)) . substr($newPassword, 5);

                                // Cập nhật mật khẩu người dùng
                                $record->update(['password' => bcrypt($newPassword)]);
                                
                                // Gửi email thông báo
                                Mail::to($record->email)->send(new PasswordResetNotification($record, $newPassword));
                                
                                // Thông báo thành công
                                Notification::make()
                                    ->title('Đặt lại mật khẩu thành công!')
                                    ->body("Mật khẩu mới đã được gửi đến email: {$record->email}")
                                    ->success()
                                    ->send();
                                    
                            } catch (\Exception $e) {
                                // Thông báo lỗi
                                Notification::make()
                                    ->title('Có lỗi xảy ra!')
                                    ->body('Không thể gửi email thông báo. Vui lòng kiểm tra cấu hình email.')
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->icon('heroicon-o-key')
                        ->requiresConfirmation()
                        ->modalHeading('Đặt lại mật khẩu')
                        ->modalDescription('Hệ thống sẽ gửi một email chứa mật khẩu mới đến tài khoản mail của người dùng. Bạn có chắc chắn muốn tiếp tục?')
                        ->disabled(fn (User $record) => Auth::user()->role != 'admin'), // Không cho phép đặt lại mật khẩu nếu không phải là quản trị viên

                    Tables\Actions\DeleteAction::make()
                        ->label('Xóa')
                        ->requiresConfirmation()
                        ->modalHeading('Xác nhận xóa người dùng')
                        ->modalDescription('Bạn có chắc chắn muốn xóa người dùng này? Hành động này không thể hoàn tác.')
                        ->color('danger')
                        ->icon('heroicon-o-trash')
                        ->disabled(fn (User $record) => $record->id === Auth::id() || $record->role != 'admin') // Không cho phép xóa chính mình
                        ,
                ])
                ->icon('heroicon-o-ellipsis-horizontal')
                ->iconButton()
                ->tooltip('Hành động')
                ->extraAttributes(['class' => 'border']),
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
            'index' => Pages\ListUsers::route('/'),
        ];
    }
}
