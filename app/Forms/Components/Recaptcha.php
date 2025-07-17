<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class Recaptcha extends Field
{
    protected string $view = 'forms.components.recaptcha';

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->dehydrated(true);
        $this->required();
        $this->label('');
    }

    public function siteKey(string $siteKey): static
    {
        $this->evaluate(function () use ($siteKey) {
            $this->getContainer()->mergeExtraAttributes([
                'data-site-key' => $siteKey,
            ]);
        });

        return $this;
    }

    public function theme(string $theme = 'light'): static
    {
        $this->evaluate(function () use ($theme) {
            $this->getContainer()->mergeExtraAttributes([
                'data-theme' => $theme,
            ]);
        });

        return $this;
    }

    public function size(string $size = 'normal'): static
    {
        $this->evaluate(function () use ($size) {
            $this->getContainer()->mergeExtraAttributes([
                'data-size' => $size,
            ]);
        });

        return $this;
    }

    public static function make(string $name = 'g-recaptcha-response'): static
    {
        $static = app(static::class, ['name' => $name]);
        $static->configure();

        return $static;
    }
}
