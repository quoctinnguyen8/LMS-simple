@php
    $siteKey = config('services.recaptcha.site_key');
    $isEnabled = config('services.recaptcha.enabled', false);
    $fieldName = $attributes['name'] ?? 'g-recaptcha-response';
    $formType = $attributes['form-type'] ?? 'general';
    $uniqueId = str_replace(['-', '_'], '', $formType) . time(); // ID duy nhất
@endphp

@if($isEnabled && $siteKey)
    <div class="form-group recaptcha-wrapper">
        <div 
            class="g-recaptcha" 
            data-sitekey="{{ $siteKey }}"
            data-theme="light"
            data-size="normal"
            data-callback="recaptchaCallback{{ $uniqueId }}"
            data-expired-callback="recaptchaExpired{{ $uniqueId }}"
        ></div>
        <input type="hidden" name="{{ $fieldName }}" id="{{ $fieldName }}-{{ $uniqueId }}" />
        
        @error($fieldName)
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    
    <script>
        // Callback khi reCAPTCHA được xác minh thành công
        function recaptchaCallback{{ $uniqueId }}(token) {
            document.getElementById('{{ $fieldName }}-{{ $uniqueId }}').value = token;
        }
        
        // Callback khi reCAPTCHA hết hạn
        function recaptchaExpired{{ $uniqueId }}() {
            document.getElementById('{{ $fieldName }}-{{ $uniqueId }}').value = '';
        }
        
        // Reset reCAPTCHA khi form submit không thành công
        function resetRecaptcha{{ $uniqueId }}() {
            if (typeof grecaptcha !== 'undefined') {
                grecaptcha.reset();
                document.getElementById('{{ $fieldName }}-{{ $uniqueId }}').value = '';
            }
        }
    </script>
@endif
