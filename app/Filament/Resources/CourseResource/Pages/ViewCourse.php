<?php

/**
 * File này không được sử dụng
 * Chỉ để tham khảo cách sử dụng các thành phần của Filament
 */

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewCourse extends ViewRecord
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Chỉnh sửa'),
            Actions\Action::make('exportStudents')
                ->label('Xuất danh sách học viên')
                ->icon('heroicon-o-document-arrow-down')
                ->color('info')
                ->action(function () {
                    // Logic xuất Excel/PDF danh sách học viên
                    // Có thể implement sau
                })
                ->visible(fn ($record) => $record->course_registrations->count() > 0),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Thông tin khóa học')
                    ->schema([
                        Infolists\Components\Split::make([
                            Infolists\Components\Grid::make(2)
                                ->schema([
                                    Infolists\Components\TextEntry::make('title')
                                        ->label('Tên khóa học')
                                        ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                        ->weight('bold'),
                                    Infolists\Components\TextEntry::make('slug')
                                        ->label('Slug')
                                        ->copyable(),
                                    Infolists\Components\TextEntry::make('category.name')
                                        ->label('Danh mục')
                                        ->badge()
                                        ->color('info'),
                                    Infolists\Components\TextEntry::make('status')
                                        ->label('Trạng thái')
                                        ->formatStateUsing(fn (string $state): string => match ($state) {
                                            'draft' => 'Nháp',
                                            'published' => 'Hiển thị',
                                            'archived' => 'Lưu trữ',
                                            default => $state,
                                        })
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'draft' => 'gray',
                                            'published' => 'success',
                                            'archived' => 'warning',
                                            default => 'gray',
                                        }),
                                    Infolists\Components\TextEntry::make('price')
                                        ->label('Giá khóa học')
                                        ->formatStateUsing(fn ($state) => $state ? number_format($state) . '₫' : 'Miễn phí')
                                        ->color(fn ($record) => $record->is_price_visible ? 'success' : 'gray'),
                                    Infolists\Components\IconEntry::make('is_price_visible')
                                        ->label('Hiển thị giá')
                                        ->boolean(),
                                    Infolists\Components\TextEntry::make('start_date')
                                        ->label('Ngày bắt đầu')
                                        ->date('d/m/Y'),
                                    Infolists\Components\TextEntry::make('end_registration_date')
                                        ->label('Hạn đăng ký')
                                        ->date('d/m/Y'),
                                    Infolists\Components\TextEntry::make('creator.name')
                                        ->label('Người tạo')
                                        ->default('Chưa xác định'),
                                ]),
                            Infolists\Components\ImageEntry::make('featured_image')
                                ->label('Hình ảnh nổi bật')
                                ->size(300)
                                ->grow(false),
                        ])->from('lg'),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('Nội dung')
                    ->schema([
                        Infolists\Components\TextEntry::make('description')
                            ->label('Mô tả ngắn')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('content')
                            ->label('Nội dung chi tiết')
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('Danh sách học viên đã đăng ký')
                    ->description(function ($record) {
                        $count = $record->course_registrations->count();
                        return "Tổng cộng: {$count} học viên";
                    })
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('course_registrations')
                            ->label('')
                            ->schema([
                                Infolists\Components\Grid::make(4)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('student_name')
                                            ->label('Tên học viên')
                                            ->weight('bold'),
                                        Infolists\Components\TextEntry::make('student_phone')
                                            ->label('Số điện thoại'),
                                        Infolists\Components\TextEntry::make('student_email')
                                            ->label('Email')
                                            ->default('Chưa cung cấp'),
                                        Infolists\Components\TextEntry::make('registration_date')
                                            ->label('Ngày đăng ký')
                                            ->dateTime('d/m/Y H:i'),
                                        Infolists\Components\TextEntry::make('status')
                                            ->label('Trạng thái')
                                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                                'pending' => 'Đang chờ',
                                                'confirmed' => 'Đã xác nhận',
                                                'canceled' => 'Đã hủy',
                                                'completed' => 'Đã hoàn thành',
                                                default => $state,
                                            })
                                            ->badge()
                                            ->color(fn (string $state): string => match ($state) {
                                                'pending' => 'warning',
                                                'confirmed' => 'success',
                                                'canceled' => 'danger',
                                                'completed' => 'info',
                                                default => 'gray',
                                            }),
                                        Infolists\Components\TextEntry::make('payment_status')
                                            ->label('Thanh toán')
                                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                                'paid' => 'Đã thanh toán',
                                                'unpaid' => 'Chưa thanh toán',
                                                'refunded' => 'Đã hoàn tiền',
                                                default => $state,
                                            })
                                            ->badge()
                                            ->color(fn (string $state): string => match ($state) {
                                                'paid' => 'success',
                                                'unpaid' => 'danger',
                                                'refunded' => 'warning',
                                                default => 'gray',
                                            }),
                                        Infolists\Components\TextEntry::make('student_birth_date')
                                            ->label('Ngày sinh')
                                            ->date('d/m/Y')
                                            ->default('Chưa cung cấp'),
                                        Infolists\Components\TextEntry::make('student_gender')
                                            ->label('Giới tính')
                                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                                'male' => 'Nam',
                                                'female' => 'Nữ',
                                                'other' => 'Khác',
                                                default => 'Chưa xác định',
                                            }),
                                    ]),
                            ])
                            ->contained(false)
                            ->grid(1),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('Thống kê')
                    ->schema([
                        Infolists\Components\Grid::make(4)
                            ->schema([
                                Infolists\Components\TextEntry::make('total_registrations')
                                    ->label('Tổng đăng ký')
                                    ->getStateUsing(fn ($record) => $record->course_registrations->count())
                                    ->badge()
                                    ->color('info'),
                                Infolists\Components\TextEntry::make('confirmed_registrations')
                                    ->label('Đã xác nhận')
                                    ->getStateUsing(fn ($record) => $record->course_registrations->where('status', 'confirmed')->count())
                                    ->badge()
                                    ->color('success'),
                                Infolists\Components\TextEntry::make('paid_registrations')
                                    ->label('Đã thanh toán')
                                    ->getStateUsing(fn ($record) => $record->course_registrations->where('payment_status', 'paid')->count())
                                    ->badge()
                                    ->color('success'),
                                Infolists\Components\TextEntry::make('total_revenue')
                                    ->label('Doanh thu dự kiến')
                                    ->getStateUsing(function ($record) {
                                        $paidCount = $record->course_registrations->where('payment_status', 'paid')->count();
                                        return number_format($paidCount * $record->price) . '₫';
                                    })
                                    ->badge()
                                    ->color('warning'),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}
