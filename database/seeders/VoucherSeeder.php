<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    public function run()
    {
        Voucher::create([
            'name' => 'Voucher Diskon 50%',
            'cost' => 20,
            'description' => 'Diskon 50% s/d 50rb',
        ]);

        // Tambahkan lebih banyak voucher jika perlu
        Voucher::create([
            'name' => 'Voucher Diskon 30%',
            'cost' => 30,
            'description' => 'Diskon 30% s/d 30rb',
        ]);
        // Tambahkan lebih banyak voucher jika perlu
    }
}
