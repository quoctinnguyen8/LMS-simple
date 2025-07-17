@php
    $siteKey = config('services.recaptcha.site_key');
    $isEnabled = config('services.recaptcha.enabled', false);
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    @if($isEnabled && $siteKey)
        <div
            class="recaptcha-container"
            wire:ignore
            x-data="recaptchaComponent(@js($getStatePath()), @js($siteKey))"
        >
            <div 
                id="recaptcha-{{ $getStatePath() }}"
                class="g-recaptcha"
                data-sitekey="{{ $siteKey }}"
                data-theme="{{ $getContainer()->getExtraAttributes()['data-theme'] ?? 'light' }}"
                data-size="{{ $getContainer()->getExtraAttributes()['data-size'] ?? 'normal' }}"
                data-callback="recaptchaCallback{{ str_replace(['-', '.'], '', $getStatePath()) }}"
            ></div>
            
            <script>
                function recaptchaCallback{{ str_replace(['-', '.'], '', $getStatePath()) }}(token) {
                    @this.set('{{ $getStatePath() }}', token);
                }
                
                window.recaptchaComponent = function(statePath, siteKey) {
                    return {
                        init() {
                            // Load reCAPTCHA script if not already loaded
                            if (!window.grecaptcha) {
                                const script = document.createElement('script');
                                script.src = 'https://www.google.com/recaptcha/api.js';
                                script.async = true;
                                script.defer = true;
                                document.head.appendChild(script);
                                
                                script.onload = () => {
                                    this.renderRecaptcha();
                                };
                            } else {
                                this.renderRecaptcha();
                            }
                        },
                        
                        renderRecaptcha() {
                            // Delay rendering to ensure DOM is ready
                            setTimeout(() => {
                                if (window.grecaptcha && window.grecaptcha.render) {
                                    const container = document.getElementById('recaptcha-' + statePath);
                                    if (container && !container.hasChildNodes()) {
                                        try {
                                            window.grecaptcha.render(container, {
                                                'sitekey': siteKey,
                                                'theme': container.dataset.theme || 'light',
                                                'size': container.dataset.size || 'normal',
                                                'callback': window['recaptchaCallback' + statePath.replace(/[-\.]/g, '')]
                                            });
                                        } catch (error) {
                                            console.error('reCAPTCHA render error:', error);
                                        }
                                    }
                                }
                            }, 100);
                        }
                    }
                }
            </script>
        </div>
    @else
        <div style="padding: 1rem; background-color: #FFFBEB; border: 1px solid #FEF08A; border-radius: 0.5rem;">
            <div style="display: flex; align-items: center;">
                <svg style="width: 1.25rem; height: 1.25rem; color: #FBBF24; margin-right: 0.5rem;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span style="font-size: 0.875rem; color: #A16207;">
                    reCAPTCHA chưa được cấu hình hoặc đã bị tắt
                </span>
            </div>
        </div>
    @endif
</x-dynamic-component>
