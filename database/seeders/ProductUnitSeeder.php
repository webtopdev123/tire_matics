<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $merchants = DB::table('nano_merchant')
            ->select('merchant_id')
            ->get();

        foreach ($merchants as $merchant) {
            $unitExists = DB::table('nano_product_unit')
                ->where('unit_name', 'Normal')
                ->where('merchant_id', $merchant->merchant_id)
                ->exists();

            if (!$unitExists) {
                DB::table('nano_product_unit')->insert([
                    'unit_name' => 'Normal',
                    'merchant_id' => $merchant->merchant_id,
                ]);
            }
        }
    }
}
