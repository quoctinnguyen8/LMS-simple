<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Kiểm tra xem reCAPTCHA có được bật không
        if (!config('services.recaptcha.enabled', false)) {
            return; // Bỏ qua validation nếu reCAPTCHA bị tắt
        }

        // Kiểm tra secret key có tồn tại không
        $secretKey = config('services.recaptcha.secret_key');
        if (empty($secretKey)) {
            $fail('Cấu hình reCAPTCHA không hợp lệ.');
            return;
        }

        // Kiểm tra token có được cung cấp không
        if (empty($value)) {
            $fail('Vui lòng xác minh reCAPTCHA.');
            return;
        }

        try {
            // Gửi request đến Google reCAPTCHA API
            $response = Http::asForm()
                ->timeout(10)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $secretKey,
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);

            $result = $response->json();

            // Kiểm tra response từ Google
            if (!isset($result['success']) || !$result['success']) {
                $errorCodes = $result['error-codes'] ?? [];
                
                // Xử lý các lỗi cụ thể
                if (in_array('timeout-or-duplicate', $errorCodes)) {
                    $fail('reCAPTCHA đã hết hạn. Vui lòng tải lại trang.');
                } elseif (in_array('invalid-input-response', $errorCodes)) {
                    $fail('reCAPTCHA không hợp lệ. Vui lòng thử lại.');
                } else {
                    $fail('Xác minh reCAPTCHA thất bại. Vui lòng thử lại.');
                }
                return;
            }

            // Kiểm tra score (nếu là reCAPTCHA v3)
            if (isset($result['score'])) {
                $minScore = config('services.recaptcha.min_score', 0.5);
                if ($result['score'] < $minScore) {
                    $fail('Xác minh reCAPTCHA thất bại. Vui lòng thử lại.');
                    return;
                }
            }

        } catch (\Exception $e) {
            Log::error('reCAPTCHA validation error: ' . $e->getMessage());
            $fail('Không thể xác minh reCAPTCHA. Vui lòng thử lại.');
        }
    }
}
