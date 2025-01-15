<?php

namespace Database\Seeders;

use App\Enums\AgreementVersionStatus;
use Illuminate\Database\Seeder;
use App\Enums\Status;
use App\Enums\UserType;
use App\Models\Agreement;
use App\Models\AgreementVersion;
use App\Models\Page;
use Illuminate\Support\Str;

class ContentTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = ['Hakkımızda', 'İletişim'];

        foreach ($pages as $page) {
            Page::create([
                'status' => Status::ACTIVE,
                'title' => $page,
                'slug' => Str::slug($page),
                'content' => $page
            ]);
        }

        $agreements = [
            [
                'title' => 'Gizlilik Politikası',
                'description' => 'Gizlilik Politikası',
                'show_on_register' => false,
                'show_on_payment' => false,
                'user_type' => UserType::USER
            ],
            [
                'title' => 'Çerez Politikası',
                'description' => 'Çerez Politikası',
                'show_on_register' => false,
                'show_on_payment' => false,
                'user_type' => UserType::USER
            ],
            [
                'title' => 'KVKK Politikası',
                'description' => 'KVKK Politikası',
                'show_on_register' => true,
                'show_on_payment' => false,
                'user_type' => UserType::USER
            ],
            [
                'title' => 'Ziyaretçi Aydınlatma Metni',
                'description' => 'Ziyaretçi Aydınlatma Metni',
                'show_on_register' => false,
                'show_on_payment' => false,
                'user_type' => UserType::USER
            ],
            [
                'title' => 'Üyelik Sözleşmesi',
                'description' => 'Üyelik Sözleşmesi',
                'show_on_register' => true,
                'show_on_payment' => false,
                'user_type' => UserType::USER
            ],
            [
                'title' => 'Mesafeli Satış Sözleşmesi',
                'description' => 'Mesafeli Satış Sözleşmesi',
                'show_on_register' => false,
                'show_on_payment' => true,
                'user_type' => UserType::USER
            ],
            [
                'title' => 'Kullanıcı Aydınlatma Metni',
                'description' => 'Kullanıcı Aydınlatma Metni',
                'show_on_register' => false,
                'show_on_payment' => false,
                'user_type' => UserType::ADMIN
            ],
            [
                'title' => 'Kullanım Sözleşmesi',
                'description' => 'Kullanım Sözleşmesi',
                'show_on_register' => false,
                'show_on_payment' => false,
                'user_type' => UserType::ADMIN
            ],
        ];

        foreach ($agreements as $agreement) {
            $createdAgreement = Agreement::create([
                'id' => Str::uuid(), // UUID eklendi
                'status' => Status::ACTIVE,
                'title' => $agreement['title'],
                'slug' => Str::slug($agreement['title']),
                'description' => $agreement['description'],
                'show_on_register' => $agreement['show_on_register'],
                'show_on_payment' => $agreement['show_on_payment'],
                'user_type' => $agreement['user_type']
            ]);

            AgreementVersion::create([
                'id' => Str::uuid(), // UUID eklendi
                'agreement_id' => $createdAgreement->id,
                'status' => AgreementVersionStatus::PUBLISHED,
                'version' => 'Versiyon 1',
                'content' => 'Bu ' . $agreement['title'] . ' için Versiyon 1 içeriğidir.',
                'published_at' => now(),
                'require_acceptance' => $agreement['show_on_register'] || $agreement['show_on_payment'],
                'block_access' => $agreement['show_on_register'], // Sadece kayıt sırasında gösterilenler için zorunlu
                'send_notification' => true
            ]);
        }
    }
}
