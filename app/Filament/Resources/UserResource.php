<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Mail\AccountDeletionNotification;
use App\Mail\EmailChangeNotification;
use App\Mail\PasswordResetNotification;
use App\Mail\UserSuspensionNotification;
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
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'active' => 'Hoạt động',
                        'suspended' => 'Đình chỉ',
                        'inactive' => 'Không hoạt động',
                        default => 'Không xác định',
                    })
                    ->color(fn ($state) => match ($state) {
                        'active' => 'success',
                        'suspended' => 'danger',
                        'inactive' => 'warning',
                        default => 'gray',
                    })
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
                        'suspended' => 'Đình chỉ',
                        'inactive' => 'Không hoạt động',
                    ])
                    ->default('active'),
                Tables\Filters\SelectFilter::make('role')
                    ->label('Vai trò')
                    ->options([
                        'admin' => 'Quản trị viên',
                        'subadmin' => 'Quản trị viên phụ',
                        'user' => 'Người dùng',
                    ]),
            ])
            ->actions([
                // nhóm các hành động vào dropdown
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
                                ->disabled(fn (User $record) => Auth::user()?->role != 'admin') // Không cho phép sửa email nếu không phải là quản trị viên
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
                        ->icon('heroicon-o-pencil')
                        ->mutateRecordDataUsing(function (array $data, User $record): array {
                            // Lưu email cũ vào session hoặc cache để sử dụng sau
                            session(['edit_user_' . $record->id . '_old_email' => $record->email]);
                            return $data;
                        })
                        ->after(function (User $record, array $data) {
                            // Lấy email cũ từ session
                            $oldEmail = session('edit_user_' . $record->id . '_old_email');
                            
                            // Xóa session sau khi sử dụng
                            session()->forget('edit_user_' . $record->id . '_old_email');
                            
                            // Kiểm tra xem email có thay đổi không
                            if ($oldEmail && $oldEmail !== $data['email']) {
                                try {
                                    // Gửi email thông báo đến email cũ
                                    Mail::to($oldEmail)->send(new EmailChangeNotification(
                                        $record, 
                                        $oldEmail, 
                                        $data['email'], 
                                        Auth::user()
                                    ));
                                    
                                    Notification::make()
                                        ->title('Cập nhật thông tin thành công!')
                                        ->body("Đã gửi email thông báo thay đổi địa chỉ email đến: {$oldEmail}")
                                        ->success()
                                        ->send();
                                        
                                } catch (\Exception $e) {
                                    Notification::make()
                                        ->title('Cập nhật thành công nhưng có lỗi gửi email')
                                        ->body('Không thể gửi email thông báo thay đổi địa chỉ email.')
                                        ->warning()
                                        ->send();
                                }
                            } else {
                                Notification::make()
                                    ->title('Cập nhật thông tin thành công!')
                                    ->body('Thông tin người dùng đã được cập nhật.')
                                    ->success()
                                    ->send();
                            }
                        })
                        ->successNotification(null)
                        // Chỉ cho phép quản trị viên sửa
                        ->disabled(fn (User $record) => Auth::user()->role != 'admin'),

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
                        // Không cho phép đặt lại mật khẩu nếu không phải là quản trị viên hoặc người dùng không hoạt động
                        ->disabled(fn (User $record) => Auth::user()->role != 'admin' || $record->status != 'active'),

                    // Action đình chỉ/kích hoạt user
                    Tables\Actions\Action::make('toggle_suspension')
                        ->label(fn (User $record) => $record->status === 'suspended' ? 'Kích hoạt lại' : 'Đình chỉ')
                        ->icon(fn (User $record) => $record->status === 'suspended' ? 'heroicon-o-check-circle' : 'heroicon-o-no-symbol')
                        ->color(fn (User $record) => $record->status === 'suspended' ? 'success' : 'warning')
                        ->requiresConfirmation()
                        ->modalHeading(fn (User $record) => $record->status === 'suspended' ? 'Xác nhận kích hoạt lại người dùng' : 'Xác nhận đình chỉ người dùng')
                        ->modalDescription(fn (User $record) => $record->status === 'suspended' 
                            ? 'Người dùng sẽ có thể đăng nhập và sử dụng hệ thống trở lại. Bạn có chắc chắn?' 
                            : 'Người dùng sẽ bị tạm dừng truy cập vào hệ thống. Bạn có chắc chắn?')
                        ->form([
                            Forms\Components\Textarea::make('reason')
                                ->label('Lý do')
                                ->required()
                                ->placeholder(fn (User $record) => $record->status === 'suspended' 
                                    ? 'Nhập lý do kích hoạt lại tài khoản...' 
                                    : 'Nhập lý do đình chỉ tài khoản...')
                                ->maxLength(500)
                        ])
                        ->action(function (User $record, array $data) {
                            $isCurrentlySuspended = $record->status === 'suspended';
                            $action = $isCurrentlySuspended ? 'reactivate' : 'suspend';
                            
                            // Cập nhật trạng thái
                            $record->update([
                                'status' => $isCurrentlySuspended ? 'active' : 'suspended',
                                'suspended_at' => $isCurrentlySuspended ? null : now(),
                                'suspended_by' => $isCurrentlySuspended ? null : Auth::id(),
                                'suspension_reason' => $data['reason']
                            ]);
                            
                            // Gửi email thông báo
                            try {
                                Mail::to($record->email)->send(new UserSuspensionNotification(
                                    $record, 
                                    Auth::user(), 
                                    $data['reason'],
                                    $action
                                ));
                                
                                $message = $isCurrentlySuspended 
                                    ? "Đã kích hoạt lại tài khoản và gửi email thông báo đến: {$record->email}"
                                    : "Đã đình chỉ tài khoản và gửi email thông báo đến: {$record->email}";
                                    
                                Notification::make()
                                    ->title($isCurrentlySuspended ? 'Kích hoạt thành công!' : 'Đình chỉ thành công!')
                                    ->body($message)
                                    ->success()
                                    ->send();
                                    
                            } catch (\Exception $e) {
                                $message = $isCurrentlySuspended 
                                    ? 'Đã kích hoạt lại tài khoản nhưng không thể gửi email thông báo.'
                                    : 'Đã đình chỉ tài khoản nhưng không thể gửi email thông báo.';
                                    
                                Notification::make()
                                    ->title('Lỗi gửi email')
                                    ->body($message)
                                    ->warning()
                                    ->send();
                            }
                        })
                        ->disabled(fn (User $record) => $record->id === Auth::id() || Auth::user()->role != 'admin'), // Không cho phép tự đình chỉ mình

                    // Action xóa (chỉ hiển thị cho admin và chỉ với user không có dữ liệu liên quan)
                    // Tables\Actions\DeleteAction::make()
                    //     ->label('Xóa vĩnh viễn')
                    //     ->requiresConfirmation()
                    //     ->modalHeading('⚠️ NGUY HIỂM: Xóa người dùng vĩnh viễn')
                    //     ->modalDescription('Hành động này sẽ xóa vĩnh viễn người dùng và TẤT CẢ dữ liệu liên quan. Điều này KHÔNG THỂ hoàn tác!')
                    //     ->color('danger')
                    //     ->icon('heroicon-o-trash')
                    //     ->disabled(fn (User $record) => 
                    //         $record->id === Auth::id() || 
                    //         Auth::user()->role != 'admin' ||
                    //         $record?->course_registrations()?->exists() ||
                    //         $record?->room_booking_groups()?->exists() ||
                    //         $record?->room_bookings()?->exists()
                    //     )
                    //     ,
                ])
                ->icon('heroicon-o-ellipsis-horizontal')
                ->iconButton()
                ->tooltip('Hành động')
                ->extraAttributes(['class' => 'border']),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
