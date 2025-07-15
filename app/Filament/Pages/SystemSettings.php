<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Helpers\SettingHelper;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;

class SystemSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string $view = 'filament.pages.system-settings';
    protected static ?string $navigationLabel = 'Cài đặt hệ thống';
    protected static ?string $title = 'Cài đặt hệ thống';
    protected static ?int $navigationSort = 99;
    protected static ?string $navigationGroup = 'Trang web';

    public ?array $data = [];

    public function mount(): void
    {
        try {
            $settings = SettingHelper::all();
            
            $this->form->fill([
                'center_name' => $settings['center_name'] ?? '',
                'address' => $settings['address'] ?? '',
                'phone' => $settings['phone'] ?? '',
                'email' => $settings['email'] ?? '',
                'logo' => $settings['logo'] ?? '',
                'description' => $settings['description'] ?? '',
                'google_map' => $settings['google_map'] ?? '',
                'facebook_fanpage' => $settings['facebook_fanpage'] ?? '',
                'zalo_embed' => $settings['zalo_embed'] ?? '',
                'custom_css' => $settings['custom_css'] ?? '',
                'custom_js' => $settings['custom_js'] ?? '',
            ]);
        } catch (\Exception $e) {
            // Fallback nếu có lỗi
            $this->form->fill([
                'center_name' => '',
                'address' => '',
                'phone' => '',
                'email' => '',
                'logo' => '',
                'description' => '',
                'google_map' => '',
                'facebook_fanpage' => '',
                'zalo_embed' => '',
                'custom_css' => '',
                'custom_js' => '',
            ]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin cơ bản')
                    ->description('Thông tin cơ bản của trung tâm')
                    ->schema([
                        TextInput::make('center_name')
                            ->label('Tên trung tâm')
                            ->required()
                            ->maxLength(255),
                        
                        TextInput::make('phone')
                            ->label('Số điện thoại')
                            ->tel()
                            ->maxLength(20),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        Textarea::make('address')
                            ->label('Địa chỉ')
                            ->rows(3),        
                        ])
                    ->columns(2),

                Section::make('Hình ảnh & Nội dung')
                    ->description('Logo và nội dung giới thiệu')
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo trung tâm')
                            ->image()
                            ->directory('logos')
                            ->visibility('public')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ]),
                        
                        RichEditor::make('description')
                            ->label('Giới thiệu trung tâm')
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make('Tích hợp mạng xã hội')
                    ->description('Nhúng bản đồ, fanpage và zalo')
                    ->schema([
                        Textarea::make('google_map')
                            ->label('Nhúng Google Maps')
                            ->placeholder('Dán mã embed HTML của Google Maps')
                            ->rows(4)
                            ->helperText('Lấy mã nhúng từ Google Maps và dán vào đây')
                            ->extraAttributes([
                                'style' => 'font-family: "Fira Code", "JetBrains Mono", "Monaco", "Cascadia Code", "Roboto Mono", monospace; font-size: 14px;'
                            ]),
                        
                        Textarea::make('facebook_fanpage')
                            ->label('Nhúng Facebook Fanpage')
                            ->placeholder('Dán mã embed HTML của Facebook Fanpage')
                            ->rows(4)
                            ->helperText('Lấy mã nhúng từ Facebook và dán vào đây')
                            ->extraAttributes([
                                'style' => 'font-family: "Fira Code", "JetBrains Mono", "Monaco", "Cascadia Code", "Roboto Mono", monospace; font-size: 14px;'
                            ]),
                        
                        Textarea::make('zalo_embed')
                            ->label('Nhúng Zalo')
                            ->placeholder('Dán mã embed HTML của Zalo')
                            ->rows(4)
                            ->helperText('Lấy mã nhúng từ Zalo và dán vào đây')
                            ->extraAttributes([
                                'style' => 'font-family: "Fira Code", "JetBrains Mono", "Monaco", "Cascadia Code", "Roboto Mono", monospace; font-size: 14px;'
                            ]),
                    ]),

                Section::make('Tùy chỉnh giao diện')
                    ->description('CSS và JavaScript tùy chỉnh. Chỉ chỉnh sửa nếu bạn là lập trình viên hoặc có kiến thức về CSS/JS')
                    ->schema([
                        Textarea::make('custom_css')
                            ->label('CSS tùy chỉnh')
                            ->rows(8)
                            ->extraAttributes([
                                'style' => 'font-family: "Fira Code", "JetBrains Mono", "Monaco", "Cascadia Code", "Roboto Mono", monospace; font-size: 14px;'
                            ])
                            ->placeholder('/* CSS tùy chỉnh */
body {
    font-family: \'Arial\', sans-serif;
}')
                            ->helperText('Thêm CSS tùy chỉnh để thay đổi giao diện website'),
                        
                        Textarea::make('custom_js')
                            ->label('JavaScript tùy chỉnh')
                            ->rows(8)
                            ->extraAttributes([
                                'style' => 'font-family: "Fira Code", "JetBrains Mono", "Monaco", "Cascadia Code", "Roboto Mono", monospace; font-size: 14px;'
                            ])
                            ->placeholder('// JavaScript tùy chỉnh
console.log(\'Hello World\');')
                            ->helperText('Thêm JavaScript tùy chỉnh để thêm chức năng cho website'),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Lưu cài đặt')
                ->extraAttributes(['style' => 'margin: 10px 0;'])
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            SettingHelper::set($key, $value);
        }

        // Clear all settings cache after update
        SettingHelper::clearCache();

        Notification::make()
            ->title('Cập nhật thành công')
            ->body('Các cài đặt hệ thống đã được cập nhật.')
            ->success()
            ->send();
    }
}
