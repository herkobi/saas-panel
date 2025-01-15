<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enums\Status;
use App\Models\Orderstatus;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'code' => 'PENDING_PAYMENT',
                'title' => 'Ödeme Bekleniyor',
                'description' => 'Ödeme kaydı oluşturuldu, ödeme/dekont bekleniyor'
            ],
            [
                'code' => 'REVIEW',
                'title' => 'İncelemede',
                'description' => 'Dekont yüklendi, inceleme bekleniyor'
            ],
            [
                'code' => 'APPROVED',
                'title' => 'Onaylandı',
                'description' => 'Ödeme onaylandı, abonelik aktif'
            ],
            [
                'code' => 'REJECTED',
                'title' => 'Reddedildi',
                'description' => 'Ödeme reddedildi'
            ],
            [
                'code' => 'EXPIRED',
                'title' => 'Süresi Doldu',
                'description' => 'Ödeme süresi doldu'
            ],
            [
                'code' => 'INVOICED',
                'title' => 'Faturalandırıldı',
                'description' => 'Ödemeye ait fatura oluşturuldu.'
            ]
        ];

        foreach ($statuses as $status) {
            Orderstatus::create([
                'status' => Status::ACTIVE->value,
                'code' => $status['code'],
                'title' => $status['title'],
                'description' => $status['description'],
            ]);
        }
    }
}
