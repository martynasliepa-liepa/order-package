<?php

namespace Praktika\Orders\Database\Seeders; // Tavo paketo namespace

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    public function run(): void
    {
        //  busenomis lentele duomenys
        $statuses = [
            ['id' => 1, 'name' => 'Naujas', 'slug' => 'new'],
            ['id' => 2, 'name' => 'Apdorojamas', 'slug' => 'processing'],
            ['id' => 3, 'name' => 'Išsiųstas', 'slug' => 'shipped'],
            ['id' => 4, 'name' => 'Įvykdytas', 'slug' => 'completed'],
            ['id' => 5, 'name' => 'Atšauktas', 'slug' => 'cancelled'],
        ];

        foreach ($statuses as $status) {
            // apsauga nuo dubliaavimo irsasome duomenis
            DB::table('order_statuses')->updateOrInsert(
                ['id' => $status['id']],
                [
                    'name' => $status['name'],
                    'slug' => $status['slug'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}