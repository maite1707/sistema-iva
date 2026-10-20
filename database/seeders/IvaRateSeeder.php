<?php

namespace Database\Seeders;

use App\Models\IvaRate;
use Illuminate\Database\Seeder;

class IvaRateSeeder extends Seeder
{
    public function run(): void
    {
        $rates = [
            ['name' => 'IVA General', 'percentage' => 13.00, 'is_default' => true],
            ['name' => 'Exento', 'percentage' => 0.00, 'is_default' => false],
        ];

        foreach ($rates as $rate) {
            IvaRate::updateOrCreate(
                ['name' => $rate['name']],
                $rate
            );
        }
    }
}