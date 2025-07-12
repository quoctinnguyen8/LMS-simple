<div class="space-y-4">
    <div class="pb-3">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
            Danh sách học viên đăng ký
        </h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Khóa học: {{ $course->title }} | Ngày bắt đầu: {{ \Carbon\Carbon::parse($course->start_date)->format('d/m/Y') }}
        </p>
        
        @php
            $paidCount = $registrations->where('payment_status', 'paid')->count();
            $pendingCount = $registrations->where('payment_status', 'pending')->count();
            $cancelledCount = $registrations->where('payment_status', 'cancelled')->count();
            
            // Tính doanh thu dựa trên giá thực tế khi đăng ký, fallback về giá hiện tại nếu chưa có
            $totalRevenue = $registrations->where('payment_status', 'paid')->sum(function($registration) use ($course) {
                return $registration->actual_price ?? $course->price;
            });
        @endphp
        
        <div style="margin-top: 12px; display: flex; flex-wrap: wrap; gap: 16px;">
            <div style="flex: 1; min-width: 200px; background-color: #f0fdf4; border-radius: 8px; padding: 12px;">
                <div style="color: #16a34a; font-size: 14px; font-weight: 500;">Đã thanh toán</div>
                <div style="color: #14532d; font-size: 18px; font-weight: 700;">{{ $paidCount }}</div>
            </div>
            <div style="flex: 1; min-width: 200px; background-color: #fffbeb; border-radius: 8px; padding: 12px;">
                <div style="color: #d97706; font-size: 14px; font-weight: 500;">Chờ thanh toán</div>
                <div style="color: #92400e; font-size: 18px; font-weight: 700;">{{ $pendingCount }}</div>
            </div>
            <div style="flex: 1; min-width: 200px; background-color: #fef2f2; border-radius: 8px; padding: 12px;">
                <div style="color: #dc2626; font-size: 14px; font-weight: 500;">Đã hủy</div>
                <div style="color: #991b1b; font-size: 18px; font-weight: 700;">{{ $cancelledCount }}</div>
            </div>
            <div style="flex: 1; min-width: 200px; background-color: #eff6ff; border-radius: 8px; padding: 12px;">
                <div style="color: #2563eb; font-size: 14px; font-weight: 500;">Tổng doanh thu</div>
                <div style="color: #1e40af; font-size: 18px; font-weight: 700;">{{ number_format($totalRevenue) }}₫</div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <table class="w-full divide-y divide-gray-300 dark:divide-gray-600">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        STT
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Họ tên
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Sđt
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Ngày đăng ký
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Trạng thái thanh toán
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Người tạo
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($registrations as $index => $registration)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $registration->student_name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $registration->student_phone }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($registration->created_at)->format('d/m/Y H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusConfig = match($registration->payment_status) {
                                    'paid' => ['label' => 'Đã thanh toán', 'style' => 'background-color: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500;'],
                                    'pending' => ['label' => 'Chờ thanh toán', 'style' => 'background-color: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500;'],
                                    'cancelled' => ['label' => 'Đã hủy', 'style' => 'background-color: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500;'],
                                    default => ['label' => 'Không xác định', 'style' => 'background-color: #f3f4f6; color: #374151; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500;']
                                };
                            @endphp
                            <span style="{{ $statusConfig['style'] }}">
                                {{ $statusConfig['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $registration->creator ? $registration->creator->name : 'N/A' }}
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($registrations->count() > 0)
        <div class="flex justify-between items-center pt-3 text-sm text-gray-500 dark:text-gray-400">
            <div class="flex gap-4">
                <span>Tổng số học viên: <span class="font-medium text-gray-900 dark:text-white">{{ $registrations->count() }}</span></span>
                <span>Đã thanh toán: <span class="font-medium text-green-600 dark:text-green-400">{{ $paidCount }}</span></span>
                <span>Doanh thu: <span class="font-medium text-blue-600 dark:text-blue-400">{{ number_format($totalRevenue) }}₫</span></span>
            </div>
            <div>
                Cập nhật lần cuối: {{ \Carbon\Carbon::parse($course->updated_at)->format('d/m/Y H:i') }}
            </div>
        </div>
    @endif
</div>
