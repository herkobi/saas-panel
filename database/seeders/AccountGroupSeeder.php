<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountGroup;
use Illuminate\Support\Str;

class AccountGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            [
                'title' => 'Bayiler',
                'color' => 'green'
            ],
        ];

        foreach ($groups as $group) {
            AccountGroup::create($group);
        }
    }
}
