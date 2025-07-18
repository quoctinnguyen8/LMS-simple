<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;
use Illuminate\Support\Facades\Auth;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Thêm tài khoản')
            ->form([
                Forms\Components\TextInput::make('name')
                    ->label('Tên người dùng')
                    ->minLength(3)
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('email')
                    ->label('Email người dùng')
                    ->required()
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->label('Mật khẩu')
                    ->required()
                    ->password()
                    ->minLength(6)
                    ->maxLength(255),
                // password_confirmation
                Forms\Components\TextInput::make('password_confirmation')
                    ->label('Xác nhận mật khẩu')
                    ->required()
                    ->password()
                    ->minLength(6)
                    ->maxLength(255)
                    ->same('password')
                    ->dehydrated(false),
                Forms\Components\Select::make('role')
                    ->label('Vai trò')
                    ->options([
                        'admin' => 'Quản trị viên',
                        'subadmin' => 'Quản trị viên phụ',
                        'user' => 'Người dùng',
                    ])
                    ->default('subadmin')
                    ->helperText('Quản trị viên có toàn quyền quản lý hệ thống, quản trị viên phụ có quyền hạn hạn chế hơn.')
                    ->hidden(fn (Forms\Get $get) => Auth::user()?->role !== 'admin') // Chỉ hiển thị cho quản trị viên
            ])
            ->modalWidth('md')
            ->createAnother(false),
        ];
    }
}
