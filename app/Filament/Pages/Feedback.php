<?php

namespace App\Filament\Pages;

use App\Helpers\SettingHelper;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;


class Feedback extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static string $view = 'filament.pages.feedback';
    protected static ?string $navigationLabel = 'Phản hồi khách hàng';
    protected static ?string $title = 'Quản lý phản hồi khách hàng';
    protected static ?int $navigationSort = 98;
    protected static ?string $navigationGroup = 'Trang web';

    public ?array $data = [];

    public function mount(): void
    {
        try {
            $feedbackJson = SettingHelper::get('feedback', '[]');
            $feedbacks = json_decode($feedbackJson, true) ?: [];

            // Đảm bảo có ít nhất 3 feedback mặc định
            while (count($feedbacks) < 3) {
                $feedbacks[] = [
                    'name' => '',
                    'content' => '',
                    'avatar' => '',
                ];
            }

            $this->form->fill([
                'feedbacks' => $feedbacks,
            ]);
        } catch (\Exception $e) {
            // Fallback nếu có lỗi
            $this->form->fill([
                'feedbacks' => [
                    ['name' => '', 'content' => '', 'avatar' => ''],
                    ['name' => '', 'content' => '', 'avatar' => ''],
                    ['name' => '', 'content' => '', 'avatar' => ''],
                ],
            ]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Phản hồi từ khách hàng')
                    ->description('Quản lý các phản hồi từ khách hàng hiển thị trên trang chủ')
                    ->schema([
                        Repeater::make('feedbacks')
                            ->label('Danh sách phản hồi')
                            ->schema([
                                FileUpload::make('avatar')
                                    ->label('Ảnh đại diện')
                                    ->image()
                                    ->directory('feedbacks')
                                    ->visibility('public')
                                    ->acceptedFileTypes(['image/*'])
                                    ->maxSize(1024)
                                    ->imageEditor()
                                    ->imageEditorAspectRatios(['1:1'])
                                    ->helperText('Ảnh đại diện của người phản hồi (tỷ lệ 1:1)'),

                                TextInput::make('name')
                                    ->label('Tên khách hàng')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('Nhập tên khách hàng'),

                                Textarea::make('content')
                                    ->label('Nội dung phản hồi')
                                    ->required()
                                    ->rows(4)
                                    ->maxLength(500)
                                    ->placeholder('Nhập nội dung phản hồi từ khách hàng')
                                    ->helperText('Tối đa 500 ký tự'),
                            ])
                            ->collapsible()
                            ->cloneable()
                            ->reorderableWithButtons()
                            ->minItems(1)
                            ->maxItems(10)
                            ->defaultItems(3)
                            ->addActionLabel('Thêm phản hồi mới')
                            ->deleteAction(
                                fn (Forms\Components\Actions\Action $action) => $action
                                    ->requiresConfirmation()
                                    ->modalHeading('Xóa phản hồi')
                                    ->modalDescription('Bạn có chắc chắn muốn xóa phản hồi này không?')
                                    ->modalSubmitActionLabel('Xóa')
                                    ->modalCancelActionLabel('Hủy')
                            )
                    ])
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Lưu phản hồi')
                ->color('primary')
                ->icon('heroicon-m-check')
                ->extraAttributes(['style' => 'margin: 10px 0;'])
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            
            // Lọc bỏ các phản hồi rỗng
            $feedbacks = array_filter($data['feedbacks'], function ($feedback) {
                return !empty($feedback['name']) && !empty($feedback['content']);
            });

            // Reset array keys
            $feedbacks = array_values($feedbacks);

            // Lưu vào settings dưới dạng JSON
            SettingHelper::set('feedback', json_encode($feedbacks, JSON_UNESCAPED_UNICODE));

            // Clear cache
            SettingHelper::clearCache();

            Notification::make()
                ->title('Cập nhật thành công')
                ->body('Các phản hồi khách hàng đã được cập nhật.')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Có lỗi xảy ra')
                ->body('Không thể lưu phản hồi: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}