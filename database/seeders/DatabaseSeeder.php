<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\Status;
use App\Models\Admin\Admin;
use App\Models\Admin\Country;
use App\Models\Admin\Currency;
use App\Models\Admin\Gateway;
use App\Models\Admin\Page;
use App\Models\Admin\Payment;
use App\Models\Admin\State;
use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create();
        Admin::factory()->create();
        Country::factory()->create();
        Currency::factory()->create();

        // Varsayılan kayıt (EFT/Havale İle Ödeme)
        Payment::factory()->create();
        // Kredi kartı ile ödeme için kayıt
        Payment::factory()->forCreditCardPayment()->create();

        // Varsayılan kayıt (EFT/Havale İle Ödeme)
        Gateway::factory()->create();
        // Kredi kartı ile ödeme için kayıt
        Gateway::factory()->forCreditCardGateway()->create();

        $states = ['Adana', 'Adıyaman', 'Afyonkarahisar', 'Ağrı', 'Amasya', 'Ankara', 'Antalya', 'Aksaray', 'Ardahan', 'Artvin', 'Aydın', 'Balıkesir', 'Bartın', 'Batman', 'Bayburt', 'Bilecik', 'Bingöl', 'Bitlis', 'Bolu', 'Burdur', 'Bursa', 'Çanakkale', 'Çankırı', 'Çorum', 'Denizli', 'Diyarbakır', 'Düzce', 'Edirne', 'Elazığ', 'Erzincan', 'Erzurum', 'Eskişehir', 'Gaziantep', 'Giresun', 'Gümüşhane', 'Hakkâri', 'Hatay', 'Iğdır', 'Isparta', 'İstanbul', 'İzmir', 'Kahramanmaraş', 'Karabük', 'Karaman', 'Kars', 'Kastamonu', 'Kayseri', 'Kilis', 'Kırıkkale', 'Kırklareli', 'Kırşehir', 'Kocaeli', 'Konya', 'Kütahya', 'Malatya', 'Manisa', 'Mardin', 'Mersin', 'Muğla', 'Muş', 'Nevşehir', 'Niğde', 'Ordu', 'Osmaniye', 'Rize', 'Sakarya', 'Samsun', 'Siirt', 'Sinop', 'Sivas', 'Şanlıurfa', 'Şırnak', 'Tekirdağ', 'Tokat', 'Trabzon', 'Tunceli', 'Uşak', 'Van', 'Yalova', 'Yozgat', 'Zonguldak'];
        foreach($states as $state)
        {
            State::create([
                'status' => Status::ACTIVE,
                'state' => $state,
                'slug' => Str::slug($state),
                'country_id' => 1,
            ]);
        }

        $pages = ['Gizlilik Politikası', 'Çerez Politikası', 'Kullanım Sözleşmesi', 'KVKK Politikası', 'Kullanıcı Aydınlatma Metni', 'Ziyaretçi Aydınlatma Metni', 'Üyelik Sözleşmesi', 'Hizmet Sözleşmesi'];
        foreach($pages as $page)
        {
            Page::create([
                'status' => Status::ACTIVE,
                'title' => $page,
                'slug' => Str::slug($page),
                'text' => '',
            ]);
        }

    }
}
