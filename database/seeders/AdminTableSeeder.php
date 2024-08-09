<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
  
            $adminRecords = [
                'id' => 1,
                'name' => 'Super Admin',
                'email' => 'admin@gmail.com',
                'phone' => '08164329412',
                'password' => bcrypt('password'),//'$2y$10$c9JwNyFQdZPbRsqaxJBbHO9f9svv83C4y9jYzz2Nz1GDSdeIx8neW',
                'dashboard_access'=> 'YES',
                'status' => 'ACTIVE'
            ];
            Admin::insert($adminRecords);
        
    }
}
