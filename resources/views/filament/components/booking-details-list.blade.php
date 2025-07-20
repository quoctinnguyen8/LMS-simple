@php
    $duplicateDetails = $details
                        ->where('is_duplicate', true)
                        ->where('status', 'pending');
@endphp
<div class="space-y-4">
    <div class="pb-3">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Mã đặt chỗ: {{ $booking->booking_code ?? 'N/A' }} | Ngày đặt: {{ isset($booking->created_at) ? \Carbon\Carbon::parse($booking->created_at)->format('d/m/Y') : 'N/A' }}
        </p>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Yêu cầu này có <b>{{ $details->count() }}</b> ngày, 
            trong đó có <b>{{ $duplicateDetails->count() }}</b> ngày bị trùng lịch.
        </p>
        @if($duplicateDetails->count() > 0)
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Các ngày bị trùng: {{ $duplicateDetails->pluck('booking_date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d/m/Y'))->implode(', ') }}
            </p>
        @endif
    </div>
    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg overflow-y-auto"
        style="max-height: 450px;">
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
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Thao tác
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($details as $detail)
                    @php
                        $style = $detail->is_duplicate ? 'color: #92400e; font-weight: 500;' : ' color: #099840ff; font-weight: 500;';
                    @endphp
                    <tr class="{{ $detail->is_duplicate ? 'bg-gray-100' : 'hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($detail->booking_date)->format('d/m/Y') }}
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{-- Hiển thị ngày trong tuần, in hoa chữ cái đầu tiên của mỗi từ --}}
                                {{ ucwords(\Carbon\Carbon::parse($detail->booking_date)->translatedFormat('l')) }}
                            </p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                <span>
                                    {{ \Carbon\Carbon::parse($detail->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($detail->end_time)->format('H:i') }} 
                                </span>
                                <span style="{{ $style }}">
                                    @if($detail->is_duplicate)
                                        <x-heroicon-o-exclamation-triangle class="inline-block w-4 h-4 ml-1" />
                                    @else
                                        <x-heroicon-o-check class="inline-block w-4 h-4 ml-1" />
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{-- Trạng thái, nếu bị trùng thì hiện text bị trùng --}}

                                @php
                                if ($detail->is_duplicate) {
                                    $statusConfig = [
                                        'label' => 'Trùng lịch',
                                        'style' => 'background-color: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500;'
                                    ];
                                    if ($detail->status == 'rejected') {
                                        $statusConfig['label'] .= ' (đã từ chối)';
                                    }
                                } else {
                                    $statusConfig = match($detail->status) {
                                    'pending' => ['label' => 'Chờ duyệt', 'style' => 'background-color: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500;'],
                                    'approved' => ['label' => 'Đã duyệt', 'style' => 'background-color: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500;'],
                                    'rejected' => ['label' => 'Đã từ chối', 'style' => 'background-color: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500;'],
                                    'cancelled' => ['label' => $detail->cancelled_by_customer ? 'Đã hủy bởi khách hàng' : 'Đã hủy', 'style' => 'background-color: #f3f4f6; color: #374151; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500;'],
                                    default => ['text' => 'Không xác định', 'color' => 'text-gray-500'],
                                };
                                }
                                @endphp
                                <span style="{{ $statusConfig['style'] }}">
                                    {{ $statusConfig['label'] }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex space-x-2">
                                {{-- Nút từ chối cho ngày có status pending hoặc bị trùng lịch --}}
                                @if(($detail->status === 'pending' || $detail->is_duplicate) && $detail->status != 'rejected')
                                    <button 
                                        data-url="{{ route('admin.room-booking-details.reject', $detail->id) }}"
                                        type="button"
                                        onclick="rejectDetail()"
                                        style="display: inline-flex; align-items: center; padding: 6px 12px; border: none; border-radius: 6px; font-size: 12px; font-weight: 500; color: #dc2626; background-color: #fef2f2; cursor: pointer; transition: background-color 0.2s;"
                                        onmouseover="this.style.backgroundColor='#fecaca'"
                                        onmouseout="this.style.backgroundColor='#fef2f2'"
                                        title="Từ chối ngày này"
                                    >
                                        <x-heroicon-o-x-mark style="width: 16px; height: 16px; margin-right: 4px;" />
                                        Từ chối
                                    </button>
                                @endif

                                {{-- Nút hủy cho ngày có status approved --}}
                                @if($detail->status === 'approved')
                                    <button 
                                        data-url="{{ route('admin.room-booking-details.cancel', $detail->id) }}"
                                        type="button"
                                        onclick="cancelDetail()"
                                        style="display: inline-flex; align-items: center; padding: 6px 12px; border: none; border-radius: 6px; font-size: 12px; font-weight: 500; color: #d97706; background-color: #fffbeb; cursor: pointer; transition: background-color 0.2s;"
                                        onmouseover="this.style.backgroundColor='#fed7aa'"
                                        onmouseout="this.style.backgroundColor='#fffbeb'"
                                        title="Hủy ngày này"
                                    >
                                        <x-heroicon-o-x-circle style="width: 16px; height: 16px; margin-right: 4px;" />
                                        Hủy
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>