<?php

namespace Praktika\Orders\Database\Seeders; 

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    public function run(): void
    {
        //  busenomis lentele duomenys
        $statuses = [
            ['id' => 1, 'name' => 'Naujas'],
            ['id' => 2, 'name' => 'Apdorojamas'],
            ['id' => 3, 'name' => 'Išsiųstas'],
            ['id' => 4, 'name' => 'Įvykdytas'],
            ['id' => 5, 'name' => 'Atšauktas'],
        ];

        foreach ($statuses as $status) {
            // apsauga nuo dubliaavimo irsasome duomenis
            DB::table('order_statuses')->updateOrInsert(
                ['id' => $status['id']],
                [
                    'name' => $status['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}