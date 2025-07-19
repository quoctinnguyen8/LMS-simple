<?php

namespace App\Filament\Pages\Auth;

use App\Forms\Components\Recaptcha;
use App\Helpers\RecaptchaHelper;
use App\Models\User;
use App\Rules\RecaptchaRule;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
                $this->getRecaptchaFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('filament-panels::pages/auth/login.form.email.label'))
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::pages/auth/login.form.password.label'))
            ->hint(filament()->hasPasswordReset() ? new HtmlString(Blade::render('<x-filament::link :href="filament()->getRequestPasswordResetUrl()" tabindex="3"> {{ __(\'filament-panels::pages/auth/login.actions.request_password_reset.label\') }}</x-filament::link>')) : null)
            ->password()
            ->autocomplete('current-password')
            ->required()
            ->extraInputAttributes(['tabindex' => 2]);
    }

    protected function getRecaptchaFormComponent(): Component
    {
        // Chỉ hiển thị reCAPTCHA nếu được cấu hình và bật
        if (!RecaptchaHelper::isEnabled()) {
            // Trả về component ẩn nếu reCAPTCHA bị tắt
            return TextInput::make('g-recaptcha-response')
                ->hidden()
                ->default('disabled');
        }

        return RecaptchaHelper::component('g-recaptcha-response');
    }

    protected function getRememberFormComponent(): Component
    {
        return parent::getRememberFormComponent()
            ->extraInputAttributes(['tabindex' => 4]);
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $data = $this->form->getState();
            
            // Tìm user theo email
            $user = User::where('email', $data['email'])->first();
            
            // Kiểm tra user có tồn tại không
            if (!$user) {
                throw ValidationException::withMessages([
                    'data.email' => 'Email hoặc mật khẩu không đúng.',
                ]);
            }
            
            // Kiểm tra trạng thái tài khoản
            if ($user->status !== 'active') {
                $message = 'Tài khoản của bạn không thể đăng nhập. Vui lòng liên hệ quản trị viên.';

                throw ValidationException::withMessages([
                    'data.email' => $message,
                ]);
            }
            
            // Nếu tài khoản active, tiếp tục với authentication bình thường
            return parent::authenticate();
            
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'data.email' => 'Có lỗi xảy ra trong quá trình đăng nhập.',
            ]);
        }
    }
}
