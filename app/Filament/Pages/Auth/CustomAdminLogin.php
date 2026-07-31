<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class CustomAdminLogin extends BaseLogin
{
    protected static string $view = 'filament.pages.auth.custom-admin-login';
}