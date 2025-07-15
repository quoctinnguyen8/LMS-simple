<?php

namespace App\View\Components;

use App\Helpers\SettingHelper;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SystemSettings extends Component
{
    public array $settings;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->settings = SettingHelper::getSystemInfo();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.system-settings');
    }
}
