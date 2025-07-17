<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Rules\CurrentPasswordRule;

class ChangePassword extends Page implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions;
    
    protected static ?string $navigationIcon = 'heroicon-o-key';
    
    protected static ?string $navigationLabel = 'Đổi mật khẩu';
    
    protected static ?string $title = 'Đổi mật khẩu';
    
    protected static ?string $navigationGroup = 'Tài khoản';
    
    // Ẩn khỏi navigation sidebar vì đã có trong user menu
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.change-password';
    
    public ?array $data = [];
    
    public function mount(): void
    {
        $this->form->fill();
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thay đổi mật khẩu')
                    ->description('Nhập thông tin để thay đổi mật khẩu của bạn')
                    ->schema([
                        Forms\Components\TextInput::make('current_password')
                            ->label('Mật khẩu hiện tại')
                            ->password()
                            ->required()
                            ->maxWidth('md')
                            ->rules([new CurrentPasswordRule()])
                            ->validationMessages([
                                'required' => 'Vui lòng nhập mật khẩu hiện tại.',
                            ]),
                            
                        Forms\Components\TextInput::make('password')
                            ->label('Mật khẩu mới')
                            ->password()
                            ->required()
                            ->maxWidth('md')
                            ->rules([
                                'min:6',
                                'regex:/^(?=.*[a-zA-Z])(?=.*\d)/',
                                'different:current_password'
                            ])
                            ->same('password_confirmation')
                            ->validationMessages([
                                'min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
                                'regex' => 'Mật khẩu phải chứa ít nhất 1 chữ cái và 1 số.',
                                'same' => 'Xác nhận mật khẩu không khớp.',
                                'different' => 'Mật khẩu mới phải khác mật khẩu hiện tại.',
                            ]),
                            
                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Xác nhận mật khẩu mới')
                            ->password()
                            ->required()
                            ->maxWidth('md')
                            ->dehydrated(false)
                            ->validationMessages([
                                'required' => 'Vui lòng xác nhận mật khẩu mới.',
                            ]),
                    ])
                    ->columns(1)
            ])
            ->statePath('data');
    }
    
    protected function getFormActions(): array
    {
        return [
            Action::make('changePassword')
                ->label('Đổi mật khẩu')
                ->color('primary')
                ->icon('heroicon-o-key')
                ->submit('changePassword'),
        ];
    }
    
    public function changePassword(): void
    {
        try {
            $data = $this->form->getState();
            
            $user = Auth::user();
            
            // Cập nhật mật khẩu mới
            $user->update([
                'password' => Hash::make($data['password'])
            ]);
            
            // Reset form
            $this->form->fill();
            
            Notification::make()
                ->title('Thành công!')
                ->body('Mật khẩu đã được thay đổi thành công.')
                ->success()
                ->send();
                
        } catch (\Exception $e) {
            Notification::make()
                ->title('Lỗi!')
                ->body($e->getMessage() ?: 'Có lỗi xảy ra khi thay đổi mật khẩu. Vui lòng thử lại.')
                ->danger()
                ->send();
        }
    }
}
