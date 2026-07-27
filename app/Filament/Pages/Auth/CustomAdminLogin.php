<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class CustomAdminLogin extends BaseLogin
{
    // Mengarahkan Filament untuk menggunakan desain blade buatan kita, bukan bawaannya
    protected static string $view = 'filament.pages.auth.custom-admin-login';
}