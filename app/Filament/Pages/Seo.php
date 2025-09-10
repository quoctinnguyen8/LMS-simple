<?php

namespace App\Filament\Pages;

use App\Helpers\SettingHelper;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Storage;

class Seo extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static string $view = 'filament.pages.seo';

    protected static ?string $title = 'SEO Settings';

    protected static ?string $navigationLabel = 'SEO';

    protected static ?int $navigationSort = 97;

    protected static ?string $navigationGroup = 'Trang web';

    public ?array $data = [];

    public function mount(): void
    {
        try {
            $settings = SettingHelper::all();
            $this->form->fill([
                'seo_title' => $settings['seo_title'] ?? '',
                'seo_image' => $settings['seo_image'] ?? '',
                'seo_description' => $settings['seo_description'] ?? '',
                'seo_keywords' => $settings['seo_keywords'] ?? '',
                'sitemap_file' => $settings['sitemap_file'] ?? null,
                'ga_head' => $settings['ga_head'] ?? '',
                'ga_body' => $settings['ga_body'] ?? '',
            ]);
        } catch (\Exception $e) {
            // Fallback nếu có lỗi
            $this->form->fill([
                'seo_title' => '',
                'seo_image' => '',
                'seo_description' => '',
                'seo_keywords' => '',
                'sitemap_file' => null,
                'ga_head' => '',
                'ga_body' => '',
            ]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Meta Tags')
                    ->description('Cài đặt các thẻ meta cho trang web của bạn')
                    ->schema([
                        Forms\Components\FileUpload::make('seo_image')
                            ->label('Meta Image')
                            ->image()
                            ->directory('seo-images')
                            ->visibility('public')
                            ->imageEditor()
                            ->acceptedFileTypes(['image/*'])
                            ->required()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->helperText('Ảnh đại diện khi chia sẻ lên mạng xã hội. Kích thước đề xuất: 1200x630px'),

                        Forms\Components\TextInput::make('seo_title')
                            ->label('Meta Title')
                            ->helperText('Tiêu đề hiển thị trên kết quả tìm kiếm Google')
                            ->maxLength(100),

                        Forms\Components\Textarea::make('seo_description')
                            ->label('Meta Description')
                            ->helperText('Mô tả hiển thị trên kết quả tìm kiếm Google')
                            ->maxLength(300)
                            ->rows(2),

                        Forms\Components\Textarea::make('seo_keywords')
                            ->label('Meta Keywords')
                            ->helperText('Từ khóa liên quan đến nội dung trang web, phân cách bằng dấu phẩy')
                            ->maxLength(300)
                            ->rows(2),
                    ]),

                Forms\Components\Section::make('Sitemap')
                    ->description('Quản lý file sitemap.xml cho trang web')
                    ->schema([
                        Forms\Components\Placeholder::make('sitemap_info')
                            ->label('Thông tin về Sitemap')
                            ->content(view('filament.components.sitemap-info')),

                        Forms\Components\FileUpload::make('sitemap_file')
                            ->label('Upload Sitemap File')
                            ->acceptedFileTypes(['application/xml', 'text/xml'])
                            ->maxSize(5120) // 5MB
                            ->directory('sitemaps')
                            ->visibility('public')
                            ->helperText('Chỉ chấp nhận file .xml, tối đa 5MB'),
                    ]),

                Forms\Components\Section::make('Google Analytics')
                    ->description('Thêm mã Google Analytics vào trang web của bạn')
                    ->schema([
                        Forms\Components\Textarea::make('ga_head')
                            ->label('GA Code in <head>')
                            ->helperText("Thêm mã Google Analytics vào thẻ <head> của trang web. Bạn có thể lấy mã này từ trang quản trị Google Analytics của bạn.")
                            ->placeholder("<script async src='https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX'></script>")
                            ->rows(2)
                            ->maxLength(2000),

                        Forms\Components\Textarea::make('ga_body')
                            ->label('GA Code after <body>')
                            ->helperText('Thêm mã Google Analytics ngay sau thẻ <body> của trang web. Bạn có thể lấy mã này từ trang quản trị Google Analytics của bạn.')
                            ->placeholder("<script>\n  window.dataLayer = window.dataLayer || [];\n  function gtag(){dataLayer.push(arguments);}\n  gtag('js', new Date());\n\n  gtag('config', 'G-XXXXXXXXXX');\n</script>")
                            ->rows(6)
                            ->maxLength(2000),
                    ]),

            ])
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            // Save meta settings
            Setting::updateOrCreate(
                ['setting_key' => 'seo_title'],
                ['setting_value' => $data['seo_title']]
            );

            Setting::updateOrCreate(
                ['setting_key' => 'seo_description'],
                ['setting_value' => $data['seo_description']]
            );

            Setting::updateOrCreate(
                ['setting_key' => 'seo_keywords'],
                ['setting_value' => $data['seo_keywords']]
            );

            if (!empty($data['seo_image'])) {
                Setting::updateOrCreate(
                    ['setting_key' => 'seo_image'],
                    ['setting_value' => $data['seo_image']]
                );
            }
            Setting::updateOrCreate(
                ['setting_key' => 'ga_head'],
                ['setting_value' => $data['ga_head'] ?? '']
            );
            Setting::updateOrCreate(
                ['setting_key' => 'ga_body'],
                ['setting_value' => $data['ga_body'] ?? '']
            );

            // Handle sitemap upload
            if (!empty($data['sitemap_file'])) {
                $sitemapPath = $data['sitemap_file'];

                // Copy uploaded file to public root as sitemap.xml
                $content = Storage::disk('public')->get($sitemapPath);
                file_put_contents(public_path('sitemap.xml'), $content);

                // Clean up uploaded file
                Storage::disk('public')->delete($sitemapPath);

                Notification::make()
                    ->title('Sitemap đã được cập nhật thành công!')
                    ->body('File sitemap.xml đã được upload và có thể truy cập tại: ' . url('/sitemap.xml'))
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Cài đặt SEO đã được lưu thành công!')
                    ->success()
                    ->send();
            }
            SettingHelper::clearCache();
        } catch (Halt $exception) {
            return;
        }
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('save')
                ->label('Lưu cài đặt')
                ->action('save'),
        ];
    }
}
