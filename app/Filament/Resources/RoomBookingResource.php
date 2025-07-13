<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomBookingResource\Pages;
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
                    ->description('Thông tin cơ bản về đặt phòng')
                    ->schema([
                        Forms\Components\Select::make('room_id')
                            ->label('Phòng học')
                            ->relationship('room', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(2),
                        
                        Forms\Components\TextInput::make('reason')
                            ->label('Lý do đặt phòng')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        Forms\Components\DatePicker::make('start_date')
                            ->label('Ngày bắt đầu')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->columnSpan(1),
                        Forms\Components\TimePicker::make('start_time')
                            ->label('Giờ bắt đầu')
                            ->required()
                            ->native(false)
                            ->format('H:i')
                            ->displayFormat('H:i')
                            ->minutesStep(5)
                            ->seconds(false) // Ẩn giây
                            ->default('08:00') // Giá trị mặc định
                            ->columnSpan(1),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Ngày kết thúc')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->afterOrEqual('start_date')
                            ->columnSpan(1),
                        Forms\Components\TimePicker::make('end_time')
                            ->label('Giờ kết thúc')
                            ->required()
                            ->native(false)
                            ->format('H:i')
                            ->displayFormat('H:i')
                            ->minutesStep(5)
                            ->seconds(false)
                            ->default('09:00')
                            ->after('start_time')
                            ->columnSpan(1),

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
                    ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\ViewRecord)
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null) // Vô hiệu hóa click để chỉnh sửa
            ->recordClasses(fn (RoomBooking $record) => match ($record->status) {
                'pending' => '', // Không tô màu cho chờ duyệt - để mặc định
                'approved' => 'bg-green-200', // Không hoạt động
                'rejected' => 'bg-gray-100 opacity-60', 
                'cancelled' => 'bg-gray-100 opacity-60',
                default => '',
            })
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
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Chờ duyệt',
                        'approved' => 'Đã duyệt',
                        'rejected' => 'Từ chối',
                        'cancelled' => 'Đã hủy',
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
                            return $query->whereIn('status', ['pending', 'approved']);
                        }
                        // Nếu có filter được chọn, áp dụng filter đó (override query mặc định)
                        return $query->whereIn('status', $data['values']);
                    })
                    ,

                SelectFilter::make('room_id')
                    ->label('Phòng học')
                    ->relationship('room', 'name')
                    ->searchable()
                    ->multiple()
                    ->preload(),

                // Bộ lọc chỉ hiển thị các đặt phòng chưa kết thúc
                // mặc định chỉ hiển thị các đặt phòng chưa kết thúc
                Tables\Filters\SelectFilter::make('booking_status')
                    ->label('Thời gian đặt phòng')
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
                    // thêm chức năng duyệt, từ chối, hủy đặt phòng
                    Tables\Actions\Action::make('approve')
                        ->label('Duyệt yêu cầu đặt phòng')
                        ->icon('heroicon-o-check')
                        ->action(function (RoomBooking $record) {
                            // nếu là record bị trùng lịch thì không cho duyệt
                            if ($record->is_duplicate) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Không thể duyệt')
                                    ->body('Yêu cầu đặt phòng này bị trùng lịch, không thể duyệt.')
                                    ->danger()
                                    ->send();
                                return;
                            }
                            $record->update(['status' => 'approved', 'approved_by' => Auth::id()]);
                            $record->refresh();
                        })
                        ->requiresConfirmation()
                        ->disabled(fn (RoomBooking $record) => $record->status != 'pending'),
                    Tables\Actions\Action::make('reject')
                        ->label('Từ chối yêu cầu đặt phòng')
                        ->icon('heroicon-o-x-mark')
                        ->action(function (RoomBooking $record) {
                            $record->update(['status' => 'rejected', 'rejected_by' => Auth::id()]);
                            $record->refresh();
                        })
                        ->requiresConfirmation()
                        ->disabled(fn (RoomBooking $record) => $record->status != 'pending'),
                    Tables\Actions\Action::make('cancel')
                        ->label('Hủy yêu cầu đặt phòng')
                        ->icon('heroicon-o-x-circle')
                        ->action(function (RoomBooking $record) {
                            $record->update(['status' => 'cancelled', 'cancelled_by' => Auth::id()]);
                            $record->refresh();
                        })
                        ->requiresConfirmation()
                        ->disabled(fn (RoomBooking $record) => $record->status != 'approved'),
                    // chỉ cho phép chỉnh sửa nếu status là 'pending' hoặc 'approved'
                    Tables\Actions\EditAction::make()
                        ->disabled(fn (RoomBooking $record) => $record->status === 'rejected' || $record->status === 'cancelled'),
                    // chỉ xóa các yêu cầu bị từ chối
                    Tables\Actions\DeleteAction::make()
                        ->label(function (RoomBooking $record) {
                            return $record->status === 'rejected' ? 'Xóa yêu cầu' : 'Không thể xóa';
                        })
                        ->tooltip(function (RoomBooking $record) {
                            return $record->status != 'rejected' ? 'Hãy từ chối yêu cầu này trước' : null;
                        })
                        ->disabled(fn (RoomBooking $record) => $record->status !== 'rejected')
                        ->requiresConfirmation()
                        ->action(function (RoomBooking $record) {
                            if ($record->status !== 'rejected') {
                                return; // Không xóa nếu không phải yêu cầu bị từ chối
                            }
                            $record->delete();
                            // xóa các chi tiết liên quan
                            $record->room_booking_details()->delete();
                        })
                ])
                ->icon('heroicon-o-ellipsis-vertical')
                ->color('gray')
                ->tooltip('Thao tác')
                ->extraAttributes(['class' => 'border'])
                ->iconButton()
            ,
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
