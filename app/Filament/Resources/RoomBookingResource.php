<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomBookingResource\Pages;
use App\Mail\BookingConfirmationNotification;
use App\Models\RoomBooking;
use App\Models\Room;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\RoomBookingService;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Mail;

class RoomBookingResource extends Resource
{
    protected static ?string $model = RoomBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Thuê phòng học';

    public static function getModelLabel(): string
    {
        return 'đặt phòng';
    }

    public static function getPluralModelLabel(): string
    {
        return 'đặt phòng';
    }

    public static function getNavigationLabel(): string
    {
        return 'Đặt phòng';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin khách hàng')
                    ->description('Thông tin liên hệ của khách hàng')
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')
                            ->label('Tên khách hàng')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('customer_phone')
                            ->label('Số điện thoại')
                            ->required()
                            // đúng format số điện thoại Việt Nam
                            ->regex('/^0[0-9]{9,10}$/')
                            ->maxLength(12),
                        Forms\Components\TextInput::make('customer_email')
                            ->label('Email khách hàng')
                            ->email()
                            ->maxLength(255),

                    ])
                    ->columns(3),
                Section::make('Thông tin đặt phòng')
                    ->description(function ($record) {
                        if ($record?->status === 'approved') {
                            return 'Thông tin cơ bản về đặt phòng - ⚠️ Yêu cầu đã được duyệt, không thể chỉnh sửa các thông tin quan trọng';
                        }
                        return 'Thông tin cơ bản về đặt phòng';
                    })
                    ->schema([
                        Forms\Components\Select::make('room_id')
                            ->label('Phòng học')
                            ->relationship('room', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn($record) => $record?->status === 'approved')
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('reason')
                            ->label('Lý do đặt phòng')
                            ->required()
                            ->maxLength(255)
                            ->disabled(fn($record) => $record?->status === 'approved')
                            ->columnSpan(2),

                        Forms\Components\DatePicker::make('start_date')
                            ->label('Ngày bắt đầu')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->disabled(fn($record) => $record?->status === 'approved')
                            ->columnSpan([
                                'default' => 2,
                                'sm' => 2,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),
                        Forms\Components\TimePicker::make('start_time')
                            ->label('Giờ bắt đầu')
                            ->required()
                            ->format('H:i')
                            ->displayFormat('H:i')
                            ->minutesStep(5)
                            ->seconds(false) // Ẩn giây
                            ->default('08:00') // Giá trị mặc định
                            ->disabled(fn($record) => $record?->status === 'approved')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    try {
                                        // Parse giờ bắt đầu và cộng thêm 1 giờ
                                        $startTime = \Carbon\Carbon::parse($state);
                                        $endTime = $startTime->copy()->addHour();
                                        $set('end_time', $endTime->format('H:i'));
                                    } catch (\Exception $e) {
                                        // Nếu có lỗi thì không làm gì
                                    }
                                }
                            })
                            ->columnSpan([
                                'default' => 2,
                                'sm' => 2,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Ngày kết thúc')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->afterOrEqual('start_date')
                            ->disabled(fn($record) => $record?->status === 'approved')
                            ->columnSpan([
                                'default' => 2,
                                'sm' => 2,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),
                        Forms\Components\TimePicker::make('end_time')
                            ->label('Giờ kết thúc')
                            ->required()
                            ->format('H:i')
                            ->displayFormat('H:i')
                            ->minutesStep(5)
                            ->seconds(false)
                            ->default('09:00') // Mặc định 1 giờ sau start_time
                            ->after('start_time')
                            ->disabled(fn($record) => $record?->status === 'approved')
                            ->columnSpan([
                                'default' => 2,
                                'sm' => 2,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),

                        Forms\Components\CheckboxList::make('repeat_days')
                            ->label('Ngày lặp lại trong tuần')
                            ->options([
                                'monday' => 'Thứ 2',
                                'tuesday' => 'Thứ 3',
                                'wednesday' => 'Thứ 4',
                                'thursday' => 'Thứ 5',
                                'friday' => 'Thứ 6',
                                'saturday' => 'Thứ 7',
                                'sunday' => 'Chủ nhật',
                            ])
                            ->columns(2) // Hiển thị 2 cột để gọn hơn
                            ->gridDirection('row') // Sắp xếp theo hàng ngang
                            ->helperText('Chọn các ngày trong tuần mà đặt phòng sẽ lặp lại từ ngày bắt đầu đến ngày kết thúc. Để trống nếu chỉ đặt một lần.')
                            ->disabled(fn($record) => $record?->status === 'approved')
                            ->columnSpan(2),
                        // notes
                        Forms\Components\Textarea::make('notes')
                            ->label('Ghi chú')
                            ->maxLength(500)
                            ->columnSpan(2),
                    ])
                    ->columns(4),

                Section::make('Thông tin quản lý')
                    ->description('Thông tin về người xử lý đặt phòng')
                    ->schema([
                        Forms\Components\Select::make('created_by')
                            ->label('Người tạo')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->disabled(),

                        Forms\Components\Select::make('approved_by')
                            ->label('Người duyệt')
                            ->relationship('approvedBy', 'name')
                            ->disabled(),

                        Forms\Components\Select::make('rejected_by')
                            ->label('Người từ chối')
                            ->relationship('rejectedBy', 'name')
                            ->disabled(),

                        Forms\Components\Select::make('cancelled_by')
                            ->label('Người hủy')
                            ->relationship('cancelledBy', 'name')
                            ->disabled(),
                    ])
                    ->visible(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\ViewRecord)
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null) // Vô hiệu hóa click để chỉnh sửa
            ->columns([
                Tables\Columns\TextColumn::make('booking_code')
                    ->label('Mã đặt phòng')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Đã sao chép mã đặt phòng'),

                Tables\Columns\TextColumn::make('room.name')
                    ->label('Phòng học')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Khách hàng')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Ngày và giờ')
                    ->icon(function ($record) {
                        if ($record->is_duplicate) {
                            return 'heroicon-o-exclamation-triangle';
                        }
                        return 'heroicon-o-calendar';
                    })
                    ->iconColor(function ($record) {
                        return $record->is_duplicate ? 'warning' : 'success';
                    })
                    ->formatStateUsing(function ($state, $record) {
                        try {
                            $startDate = \Carbon\Carbon::parse($record->start_date)->format('d/m/Y');
                            $endDate = \Carbon\Carbon::parse($record->end_date)->format('d/m/Y');

                            if ($startDate === $endDate) {
                                return $startDate;
                            } else {
                                return "{$startDate} - {$endDate}";
                            }
                        } catch (\Exception $e) {
                            return 'Lỗi ngày';
                        }
                    })
                    ->description(function ($record) {
                        try {
                            // Xử lý start_time và end_time
                            $startTime = $record->start_time;
                            $endTime = $record->end_time;

                            // Nếu là Carbon object thì format, nếu là string thì parse trước
                            if ($startTime instanceof \Carbon\Carbon) {
                                $startTime = $startTime->format('H:i');
                            } else {
                                $startTime = \Carbon\Carbon::parse($startTime)->format('H:i');
                            }

                            if ($endTime instanceof \Carbon\Carbon) {
                                $endTime = $endTime->format('H:i');
                            } else {
                                $endTime = \Carbon\Carbon::parse($endTime)->format('H:i');
                            }

                            return "{$startTime} - {$endTime}";
                        } catch (\Exception $e) {
                            return 'Lỗi giờ';
                        }
                    }),

                Tables\Columns\TextColumn::make('repeat_days')
                    ->label('Ngày lặp lại')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) {
                            return 'Không lặp lại';
                        }
                        $dayNames = [
                            'monday' => 'T2',
                            'tuesday' => 'T3',
                            'wednesday' => 'T4',
                            'thursday' => 'T5',
                            'friday' => 'T6',
                            'saturday' => 'T7',
                            'sunday' => 'CN',
                        ];
                        $arrayState = explode(',', $state);
                        $days = array_map(fn($day) => $dayNames[trim($day)] ??  $day, $arrayState);
                        return implode(', ', $days);
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Chờ duyệt',
                        'approved' => 'Đã duyệt',
                        'rejected' => 'Từ chối',
                        'cancelled_by_admin' => 'Đã hủy (Admin)',
                        'cancelled_by_customer' => 'Đã hủy (Khách hàng)',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('customer_email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('customer_phone')
                    ->label('Điện thoại')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->multiple()
                    ->default(['pending', 'approved'])
                    ->options([
                        'pending' => 'Chờ duyệt',
                        'approved' => 'Đã duyệt',
                        'rejected' => 'Từ chối',
                        'cancelled' => 'Đã hủy',
                    ])
                    ->placeholder('Mặc định: Chờ duyệt & đã duyệt')
                    // mặc định chỉ hiển thị các đặt phòng chờ duyệt và đã duyệt
                    // ->default(['pending', 'approved'])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['values'])) {
                            // Nếu không có filter nào được chọn thì chỉ hiển thị các đặt phòng chờ duyệt và đã duyệt
                            return $query->whereIn('status', ['pending', 'approved',]);
                        }
                        //nếu chọn 'cancelled', thì hiển thị 'cancelled_by_admin' và 'cancelled_by_customer'
                        if (in_array('cancelled', $data['values'])) {
                            $data['values'] = array_merge($data['values'], ['cancelled_by_admin', 'cancelled_by_customer']);
                        }
                        // Nếu có filter được chọn, áp dụng filter đó (override query mặc định)
                        return $query->whereIn('status', $data['values']);
                    }),

                SelectFilter::make('room_id')
                    ->label('Phòng học')
                    ->relationship('room', 'name')
                    ->searchable()
                    ->multiple()
                    ->preload(),

                // Bộ lọc chỉ hiển thị các đặt phòng chưa kết thúc
                // mặc định chỉ hiển thị các đặt phòng chưa kết thúc
                Tables\Filters\SelectFilter::make('booking_status')
                    ->label('Thời gian thuê')
                    ->native(false)
                    ->default('ongoing')
                    ->placeholder('Chưa kết thúc')
                    ->options([
                        'all' => 'Tất cả',
                        'ongoing' => 'Chưa kết thúc',
                        'ended' => 'Đã kết thúc',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            // Nếu không có filter nào được chọn thì chỉ hiển thị các đặt phòng chưa kết thúc
                            return $query->where('end_date', '>=', now());
                        }
                        // Nếu có filter được chọn, áp dụng filter đó (override query mặc định)
                        return match ($data['value'] ?? 'ongoing') {
                            'ongoing' => $query->where('end_date', '>=', now()),
                            'ended' => $query->where('end_date', '<', now()),
                            default => $query, // Tất cả hoặc không có filter nào được chọn
                        };
                    }),

                SelectFilter::make('repeat_days')
                    ->label('Ngày lặp lại')
                    ->options([
                        'has_repeat' => 'Có lặp lại',
                        'no_repeat' => 'Không lặp lại',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (in_array('has_repeat', $data['values'] ?? [])) {
                            $query->whereNotNull('repeat_days')->where('repeat_days', '!=', '[]');
                        }
                        if (in_array('no_repeat', $data['values'] ?? [])) {
                            $query->where(function ($q) {
                                $q->whereNull('repeat_days')->orWhere('repeat_days', '[]');
                            });
                        }
                        return $query;
                    }),
            ])
            ->actions([
                // group actions
                Tables\Actions\ActionGroup::make([

                    Tables\Actions\ViewAction::make(),

                    // chỉ cho phép chỉnh sửa nếu status là 'pending' hoặc 'approved'
                    Tables\Actions\EditAction::make()
                        ->disabled(fn(RoomBooking $record) => $record->status === 'rejected' || $record->status === 'cancelled_by_admin' || $record->status === 'cancelled_by_customer'),

                    //thêm nút chỗ action tên là xem ngày đặt phòng, nhấn vào nó hiện modal, show hết data của details
                    Tables\Actions\Action::make('view_details')
                        ->label('Xem ngày đặt phòng')
                        ->icon('heroicon-o-calendar')
                        ->modalHeading(fn($record) => 'Chi tiết đặt phòng - ' . $record->booking_code)
                        ->modalWidth('2xl')
                        ->modalSubmitAction(false)
                        ->modalCancelAction(false)
                        ->modalContent(function ($record) {
                            return view('filament.components.booking-details-list', [
                                'booking' => $record,
                                'details' => $record->room_booking_details()->orderBy('booking_date')->get(),
                            ]);
                        }),

                    // thêm chức năng duyệt, từ chối, hủy đặt phòng
                    Tables\Actions\Action::make('approve')
                        ->label('Duyệt yêu cầu đặt phòng')
                        ->icon('heroicon-o-check')
                        ->action(function (RoomBooking $record) {
                            // nếu là record bị trùng lịch thì không cho duyệt
                            if ($record->is_duplicate) {
                                Log::warning('Attempted to approve duplicate booking', [
                                    'booking_id' => $record->id,
                                    'booking_code' => $record->booking_code,
                                    'user_id' => Auth::id(),
                                    'user_name' => Auth::user()->name ?? 'Unknown',
                                    'reason' => 'Booking has duplicate schedule',
                                    'ip_address' => request()->ip(),
                                ]);

                                \Filament\Notifications\Notification::make()
                                    ->title('Không thể duyệt')
                                    ->body('Yêu cầu đặt phòng này bị trùng lịch, không thể duyệt.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $record->update(['status' => 'approved', 'approved_by' => Auth::id()]);
                            // update tất cả các chi tiết liên quan
                            // Chỉ duyệt các chi tiết có trạng thái 'pending' và ngày đặt phòng trong tương lai
                            $record->room_booking_details()
                                ->where('booking_date', '>=', now()->format('Y-m-d H:i:s'))
                                ->where('status', '=', 'pending')
                                ->update(['status' => 'approved', 'approved_by' => Auth::id()]);
                            $roomBookingService = new RoomBookingService();
                            // Cập nhật trạng thái is_duplicate cho các booking khác liên quan đến phòng này
                            $roomBookingService->updateDuplicateStatus($record->room_id);
                            $record->refresh();

                            Log::info('Room booking approved', [
                                'booking_id' => $record->id,
                                'booking_code' => $record->booking_code,
                                'room_id' => $record->room_id,
                                'room_name' => $record->room->name ?? 'Unknown',
                                'customer_name' => $record->customer_name,
                                'start_date' => $record->start_date,
                                'end_date' => $record->end_date,
                                'approved_by_user_id' => Auth::id(),
                                'approved_by_user_name' => Auth::user()->name ?? 'Unknown',
                                'ip_address' => request()->ip(),
                                'user_agent' => request()->userAgent(),
                            ]);
                            //nếu có email thì gửi thông báo
                            if ($record->customer_email) {
                                // Gửi email thông báo
                                Mail::to($record->customer_email)->send(new BookingConfirmationNotification($record));
                            }
                            \Filament\Notifications\Notification::make()
                                ->title('Yêu cầu đã được duyệt')
                                ->body('Yêu cầu đặt phòng của khách hàng đã được duyệt.')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->disabled(fn(RoomBooking $record) => $record->status != 'pending'),

                    Tables\Actions\Action::make('reject')
                        ->label('Từ chối yêu cầu đặt phòng')
                        ->icon('heroicon-o-x-mark')
                        ->action(function (RoomBooking $record) {
                            $record->update(['status' => 'rejected', 'rejected_by' => Auth::id()]);
                            // update tất cả các chi tiết liên quan
                            $updateCnt = $record->room_booking_details()->update(['status' => 'rejected', 'rejected_by' => Auth::id()]);
                            $record->refresh();

                            Log::info('Room booking rejected', [
                                'booking_id' => $record->id,
                                'booking_code' => $record->booking_code,
                                'room_id' => $record->room_id,
                                'room_name' => $record->room->name ?? 'Unknown',
                                'customer_name' => $record->customer_name,
                                'start_date' => $record->start_date,
                                'end_date' => $record->end_date,
                                'rejected_by_user_id' => Auth::id(),
                                'rejected_by_user_name' => Auth::user()->name ?? 'Unknown',
                                'ip_address' => request()->ip(),
                                'user_agent' => request()->userAgent(),
                            ]);
                            //nếu có email thì gửi thông báo
                            if ($record->customer_email) {
                                // Gửi email thông báo
                                Mail::to($record->customer_email)->send(new BookingConfirmationNotification($record));
                            }
                            \Filament\Notifications\Notification::make()
                                ->title('Yêu cầu đã bị từ chối')
                                ->body('Yêu cầu đặt phòng của khách hàng đã bị từ chối (' . $updateCnt .  ' ngày).')
                                ->danger()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->disabled(fn(RoomBooking $record) => $record->status != 'pending'),

                    Tables\Actions\Action::make('cancel')
                        ->label('Hủy yêu cầu đặt phòng')
                        ->icon('heroicon-o-x-circle')
                        ->action(function (RoomBooking $record) {
                            $record->update(['status' => 'cancelled_by_admin', 'cancelled_by' => Auth::id()]);
                            // update tất cả các chi tiết liên quan
                            // Chỉ hủy các chi tiết có trạng thái 'approved' và ngày đặt phòng trong tương lai
                            $updateCnt = $record->room_booking_details()
                                ->where('booking_date', '>=', now()->format('Y-m-d H:i:s'))
                                ->where('status', '=', 'approved')
                                ->update(['status' => 'cancelled', 'cancelled_by' => Auth::id()]);
                            // Cập nhật trạng thái is_duplicate cho các booking khác liên quan đến phòng này
                            $roomBookingService = new RoomBookingService();
                            $roomBookingService->updateDuplicateStatus($record->room_id);
                            $record->refresh();

                            Log::info('Room booking cancelled by admin', [
                                'booking_id' => $record->id,
                                'booking_code' => $record->booking_code,
                                'room_id' => $record->room_id,
                                'room_name' => $record->room->name ?? 'Unknown',
                                'customer_name' => $record->customer_name,
                                'start_date' => $record->start_date,
                                'end_date' => $record->end_date,
                                'cancelled_by_user_id' => Auth::id(),
                                'cancelled_by_user_name' => Auth::user()->name ?? 'Unknown',
                                'ip_address' => request()->ip(),
                                'user_agent' => request()->userAgent(),
                            ]);
                            //nếu có email thì gửi thông báo
                            if ($record->customer_email) {
                                // Gửi email thông báo
                                Mail::to($record->customer_email)->send(new BookingConfirmationNotification($record));
                            }
                            \Filament\Notifications\Notification::make()
                                ->title('Yêu cầu đã bị hủy')
                                ->body('Yêu cầu đặt phòng của khách hàng đã bị hủy (' . $updateCnt .  ' ngày).')
                                ->danger()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->disabled(fn(RoomBooking $record) => $record->status != 'approved'),

                    // chỉ xóa các yêu cầu bị từ chối
                    Tables\Actions\DeleteAction::make()
                        ->label(function (RoomBooking $record) {
                            $record->status === 'rejected' ? 'Xóa yêu cầu' : 'Hãy từ chối yêu cầu này trước';
                            return match ($record->status) {
                                'pending' => 'Hãy từ chối trước',
                                'approved' => 'Không thể xóa',
                                'cancelled' => 'Không thể xóa',
                                default => 'Xóa yêu cầu',
                            };
                        })
                        ->disabled(fn(RoomBooking $record) => $record->status !== 'rejected')
                        ->requiresConfirmation()
                        ->action(function (RoomBooking $record) {
                            if ($record->status !== 'rejected') {
                                Log::warning('Attempted to delete non-rejected booking', [
                                    'booking_id' => $record->id,
                                    'booking_code' => $record->booking_code,
                                    'current_status' => $record->status,
                                    'user_id' => Auth::id(),
                                    'user_name' => Auth::user()->name ?? 'Unknown',
                                    'ip_address' => request()->ip(),
                                ]);
                                return; // Không xóa nếu không phải yêu cầu bị từ chối
                            }

                            // Log trước khi xóa để giữ thông tin
                            Log::info('Room booking deleted', [
                                'booking_id' => $record->id,
                                'booking_code' => $record->booking_code,
                                'room_id' => $record->room_id,
                                'room_name' => $record->room->name ?? 'Unknown',
                                'customer_name' => $record->customer_name,
                                'customer_phone' => $record->customer_phone,
                                'customer_email' => $record->customer_email,
                                'start_date' => $record->start_date,
                                'end_date' => $record->end_date,
                                'status' => $record->status,
                                'deleted_by_user_id' => Auth::id(),
                                'deleted_by_user_name' => Auth::user()->name ?? 'Unknown',
                                'ip_address' => request()->ip(),
                                'user_agent' => request()->userAgent(),
                            ]);

                            $record->delete();
                            // xóa các chi tiết liên quan
                            $deletedCount = $record->room_booking_details()->delete();
                            \Filament\Notifications\Notification::make()
                                ->title('Yêu cầu đã bị xóa')
                                ->body("Yêu cầu đặt phòng đã bị xóa thành công. Đã xóa {$deletedCount} chi tiết liên quan.")
                                ->success()
                                ->send();
                        })
                ])
                    ->icon('heroicon-o-ellipsis-vertical')
                    ->color('gray')
                    ->tooltip('Thao tác')
                    ->extraAttributes(['class' => 'border'])
                    ->iconButton(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    // Bulk action duyệt hàng loạt
                    Tables\Actions\BulkAction::make('bulk_approve')
                        ->label('Duyệt hàng loạt')
                        ->icon('heroicon-o-check')
                        ->requiresConfirmation()
                        ->modalHeading('Duyệt các yêu cầu đặt phòng')
                        ->modalDescription('Chỉ duyệt các yêu cầu có trạng thái "Chờ duyệt" và không bị trùng lịch. Các yêu cầu khác sẽ được bỏ qua. Bạn có chắc chắn muốn tiếp tục?')
                        ->action(function ($records) {
                            $roomBookingService = new RoomBookingService();
                            $approvedCount = 0;
                            $duplicateCount = 0;
                            $approvedBookings = [];

                            foreach ($records as $record) {
                                // Chỉ duyệt những record có status = 'pending' và không bị trùng lịch
                                if ($record->status == 'pending' && !$record->is_duplicate) {
                                    $record->update([
                                        'status' => 'approved',
                                        'approved_by' => Auth::id()
                                    ]);
                                    // Update tất cả các chi tiết liên quan
                                    $record->room_booking_details()->update([
                                        'status' => 'approved',
                                        'approved_by' => Auth::id()
                                    ]);
                                    $approvedCount++;
                                    $approvedBookings[] = [
                                        'booking_id' => $record->id,
                                        'booking_code' => $record->booking_code,
                                        'room_name' => $record->room->name ?? 'Unknown',
                                        'customer_name' => $record->customer_name,
                                    ];

                                    // Cập nhật trạng thái is_duplicate cho các booking khác
                                    $roomBookingService->updateDuplicateStatus($record->room_id);
                                    //gửi email thông báo nếu có
                                    if ($record->customer_email) {
                                        Mail::to($record->customer_email)->send(new BookingConfirmationNotification($record));
                                    }
                                } elseif ($record->is_duplicate) {
                                    $duplicateCount++;
                                }
                            }

                            Log::info('Bulk approve room bookings completed', [
                                'approved_count' => $approvedCount,
                                'duplicate_count' => $duplicateCount,
                                'total_selected' => count($records),
                                'approved_bookings' => $approvedBookings,
                                'approved_by_user_id' => Auth::id(),
                                'approved_by_user_name' => Auth::user()->name ?? 'Unknown',
                                'ip_address' => request()->ip(),
                                'user_agent' => request()->userAgent(),
                            ]);

                            \Filament\Notifications\Notification::make()
                                ->title('Hoàn thành duyệt hàng loạt')
                                ->body("Đã duyệt {$approvedCount} yêu cầu." .
                                    ($duplicateCount > 0 ? " {$duplicateCount} yêu cầu bị trùng lịch không thể duyệt." : ""))
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    // Bulk action từ chối hàng loạt
                    Tables\Actions\BulkAction::make('bulk_reject')
                        ->label('Từ chối hàng loạt')
                        ->icon('heroicon-o-x-mark')
                        ->requiresConfirmation()
                        ->modalHeading('Từ chối các yêu cầu đặt phòng')
                        ->modalDescription('Chỉ từ chối các yêu cầu có trạng thái "Chờ duyệt". Các yêu cầu đã được duyệt, từ chối hoặc hủy sẽ được bỏ qua. Bạn có chắc chắn muốn tiếp tục?')
                        ->action(function ($records) {
                            $rejectedCount = 0;
                            $rejectedBookings = [];

                            foreach ($records as $record) {
                                // Chỉ từ chối những record có status = 'pending'
                                if ($record->status == 'pending') {
                                    $record->update([
                                        'status' => 'rejected',
                                        'rejected_by' => Auth::id()
                                    ]);
                                    // Update tất cả các chi tiết liên quan
                                    $record->room_booking_details()->update([
                                        'status' => 'rejected',
                                        'rejected_by' => Auth::id()
                                    ]);
                                    $rejectedCount++;
                                    $rejectedBookings[] = [
                                        'booking_id' => $record->id,
                                        'booking_code' => $record->booking_code,
                                        'room_name' => $record->room->name ?? 'Unknown',
                                        'customer_name' => $record->customer_name,
                                    ];
                                    //nếu có email thì gửi thông báo
                                    if ($record->customer_email) {
                                        Mail::to($record->customer_email)->send(new BookingConfirmationNotification($record));
                                    }
                                }
                            }

                            Log::info('Bulk reject room bookings completed', [
                                'rejected_count' => $rejectedCount,
                                'total_selected' => count($records),
                                'rejected_bookings' => $rejectedBookings,
                                'rejected_by_user_id' => Auth::id(),
                                'rejected_by_user_name' => Auth::user()->name ?? 'Unknown',
                                'ip_address' => request()->ip(),
                                'user_agent' => request()->userAgent(),
                            ]);

                            \Filament\Notifications\Notification::make()
                                ->title('Hoàn thành từ chối hàng loạt')
                                ->body("Đã từ chối {$rejectedCount} yêu cầu.")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    // Bulk action hủy hàng loạt
                    Tables\Actions\BulkAction::make('bulk_cancel')
                        ->label('Hủy hàng loạt')
                        ->icon('heroicon-o-x-circle')
                        ->requiresConfirmation()
                        ->modalHeading('Hủy các yêu cầu đặt phòng')
                        ->modalDescription('Chỉ hủy các yêu cầu có trạng thái "Đã duyệt" và chỉ hủy các ngày đặt phòng trong tương lai. Các yêu cầu khác sẽ được bỏ qua. Bạn có chắc chắn muốn tiếp tục?')
                        ->action(function ($records) {
                            $roomBookingService = new RoomBookingService();
                            $cancelledCount = 0;
                            $cancelledBookings = [];

                            foreach ($records as $record) {
                                // Chỉ hủy những record có status = 'approved'
                                if ($record->status == 'approved') {
                                    $record->update([
                                        'status' => 'cancelled_by_admin',
                                        'cancelled_by' => Auth::id()
                                    ]);
                                    // Update các chi tiết có ngày > hôm nay
                                    $record->room_booking_details()
                                        ->where('booking_date', '>=', now()->format('Y-m-d H:i:s'))
                                        ->update([
                                            'status' => 'cancelled',
                                            'cancelled_by' => Auth::id()
                                        ]);
                                    $cancelledCount++;
                                    $cancelledBookings[] = [
                                        'booking_id' => $record->id,
                                        'booking_code' => $record->booking_code,
                                        'room_name' => $record->room->name ?? 'Unknown',
                                        'customer_name' => $record->customer_name,
                                    ];

                                    // Cập nhật trạng thái is_duplicate cho các booking khác
                                    $roomBookingService->updateDuplicateStatus($record->room_id);
                                    //nếu có email thì gửi thông báo
                                    if ($record->customer_email) {
                                        Mail::to($record->customer_email)->send(new BookingConfirmationNotification($record));
                                    }
                                }
                            }

                            Log::info('Bulk cancel room bookings completed', [
                                'cancelled_count' => $cancelledCount,
                                'total_selected' => count($records),
                                'cancelled_bookings' => $cancelledBookings,
                                'cancelled_by_user_id' => Auth::id(),
                                'cancelled_by_user_name' => Auth::user()->name ?? 'Unknown',
                                'ip_address' => request()->ip(),
                                'user_agent' => request()->userAgent(),
                            ]);

                            \Filament\Notifications\Notification::make()
                                ->title('Hoàn thành hủy hàng loạt')
                                ->body("Đã hủy {$cancelledCount} yêu cầu.")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    // Bulk action xóa hàng loạt (chỉ các yêu cầu bị từ chối)
                    Tables\Actions\BulkAction::make('bulk_delete')
                        ->label('Xóa hàng loạt')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Xóa các yêu cầu đặt phòng')
                        ->modalDescription('Chỉ xóa các yêu cầu có trạng thái "Từ chối". Các yêu cầu đang chờ duyệt, đã duyệt hoặc đã hủy sẽ được bỏ qua và không thể xóa. Bạn có chắc chắn muốn tiếp tục?')
                        ->action(function ($records) {
                            $deletedCount = 0;
                            $deletedBookings = [];

                            foreach ($records as $record) {
                                // Chỉ xóa những record có status = 'rejected'
                                if ($record->status == 'rejected') {
                                    // Lưu thông tin trước khi xóa
                                    $deletedBookings[] = [
                                        'booking_id' => $record->id,
                                        'booking_code' => $record->booking_code,
                                        'room_name' => $record->room->name ?? 'Unknown',
                                        'customer_name' => $record->customer_name,
                                        'customer_phone' => $record->customer_phone,
                                        'customer_email' => $record->customer_email,
                                        'start_date' => $record->start_date,
                                        'end_date' => $record->end_date,
                                    ];

                                    // Xóa các chi tiết liên quan trước
                                    $record->room_booking_details()->delete();
                                    // Xóa record chính
                                    $record->delete();
                                    $deletedCount++;
                                }
                            }

                            Log::info('Bulk delete room bookings completed', [
                                'deleted_count' => $deletedCount,
                                'total_selected' => count($records),
                                'deleted_bookings' => $deletedBookings,
                                'deleted_by_user_id' => Auth::id(),
                                'deleted_by_user_name' => Auth::user()->name ?? 'Unknown',
                                'ip_address' => request()->ip(),
                                'user_agent' => request()->userAgent(),
                            ]);

                            \Filament\Notifications\Notification::make()
                                ->title('Hoàn thành xóa hàng loạt')
                                ->body("Đã xóa {$deletedCount} yêu cầu bị từ chối.")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(25);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoomBookings::route('/'),
            'create' => Pages\CreateRoomBooking::route('/create'),
            'view' => Pages\ViewRoomBooking::route('/{record}'),
            'edit' => Pages\EditRoomBooking::route('/{record}/edit'),
        ];
    }
}
