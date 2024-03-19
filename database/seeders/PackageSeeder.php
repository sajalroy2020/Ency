<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Basic', 'slug' => 'Basic', 'number_of_client' => '10', 'number_of_order' => '50',  'monthly_price' => 10, 'yearly_price' => 120,  'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Standard', 'slug' => 'Standard', 'number_of_client' => '50', 'number_of_order' => '500',  'monthly_price' => 50, 'yearly_price' => 600,  'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Premium', 'slug' => 'Premium', 'number_of_client' => '100', 'number_of_order' => '1000',  'monthly_price' => 100, 'yearly_price' => 1200,  'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];
        Package::insert($data);
    }
}
