<div class="space-y-4">
    <div class="pb-3">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
            Chi tiết đặt chỗ
        </h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Mã đặt chỗ: {{ $booking->booking_code ?? 'N/A' }} | Ngày đặt: {{ isset($booking->created_at) ? \Carbon\Carbon::parse($booking->created_at)->format('d/m/Y') : 'N/A' }}
        </p>

    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <table class="w-full divide-y divide-gray-300 dark:divide-gray-600">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                       Ngày đặt
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Giờ bắt đầu - Giờ kết thúc
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Trạng thái
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($details as $detail)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($detail->booking_date)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                @php
                                 $style = $detail->is_duplicate ? 'background-color: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500;' : 'background-color: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500;';
                                @endphp
                                <span style="{{ $style }}">
                                    {{ \Carbon\Carbon::parse($detail->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($detail->end_time)->format('H:i') }} 
                                    @if($detail->is_duplicate)
                                        <x-heroicon-o-exclamation-triangle class="inline-block w-4 h-4 ml-1" />
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                @php
                                $statusConfig = match($detail->status) {
                                    'pending' => ['label' => 'Chờ duyệt', 'style' => 'background-color: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500;'],
                                    'approved' => ['label' => 'Đã duyệt', 'style' => 'background-color: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500;'],
                                    'rejected' => ['label' => 'Đã từ chối', 'style' => 'background-color: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500;'],
                                    'cancelled' => ['label' => $detail->cancelled_by_customer ? 'Đã hủy bởi khách hàng' : 'Đã hủy', 'style' => 'background-color: #f3f4f6; color: #374151; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500;'],
                                    default => ['text' => 'Không xác định', 'color' => 'text-gray-500'],
                                };
                                @endphp
                                <span style="{{ $statusConfig['style'] }}">
                                    {{ $statusConfig['label'] }}
                                </span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>