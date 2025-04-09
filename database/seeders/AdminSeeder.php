<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Admin kullanıcılarını oluştur.
     */
    public function run(): void
    {
        // Ana admin kullanıcısı
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'type' => UserType::PLATFORM_ADMIN,
            'status' => true
        ]);
    }
}
