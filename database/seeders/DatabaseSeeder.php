<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin\Admin;
use App\Models\Admin\Currency;
use App\Models\Admin\Gateway;
use App\Models\Admin\Payment;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create();
        Admin::factory()->create();
        Currency::factory()->create();

        // Varsayılan kayıt (EFT/Havale İle Ödeme)
        Payment::factory()->create();
        // Kredi kartı ile ödeme için kayıt
        Payment::factory()->forCreditCardPayment()->create();

        // Varsayılan kayıt (EFT/Havale İle Ödeme)
        Gateway::factory()->create();
        // Kredi kartı ile ödeme için kayıt
        Gateway::factory()->forCreditCardGateway()->create();
    }
}
