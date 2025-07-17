<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CurrentPasswordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Kiểm tra xem user có đăng nhập không
        if (!Auth::check()) {
            $fail('Bạn cần đăng nhập để thực hiện hành động này.');
            return;
        }

        // Kiểm tra xem value có phải là string không
        if (!is_string($value)) {
            $fail('Mật khẩu phải là một chuỗi ký tự.');
            return;
        }

        // Kiểm tra mật khẩu không được để trống
        if (empty(trim($value))) {
            $fail('Vui lòng nhập mật khẩu hiện tại.');
            return;
        }

        $user = Auth::user();
        
        // Kiểm tra user có password không (trong trường hợp đặc biệt)
        if (empty($user->password)) {
            $fail('Tài khoản chưa có mật khẩu được thiết lập.');
            return;
        }

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($value, $user->password)) {
            $fail('Mật khẩu hiện tại không chính xác. Vui lòng thử lại.');
            return;
        }
    }
}
