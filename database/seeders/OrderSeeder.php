<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalUsers = 10000;
        $totalOrders = 500000;
        $chunkSize = 1000;

        // Criar usuários
        $this->command->info("Creating {$totalUsers} users...");
        $userChunks = $totalUsers / $chunkSize;

        for ($i = 0; $i < $userChunks; $i++) {
            User::factory()->count($chunkSize)->create();
        }

        $this->command->info("Successfully created {$totalUsers} users!");

        // Criar orders
        $this->command->info("Creating {$totalOrders} orders...");
        $orderChunks = $totalOrders / $chunkSize;
        $userIds = User::pluck('id')->toArray();

        for ($i = 0; $i < $orderChunks; $i++) {
            Order::factory()
                ->count($chunkSize)
                ->create([
                    'user_id' => fn() => $userIds[array_rand($userIds)]
                ]);

            if (($i + 1) % 10 === 0) {
                $this->command->info("Progress: " . (($i + 1) * $chunkSize) . " / {$totalOrders} orders created");
            }
        }

        $this->command->info("Successfully created {$totalOrders} orders distributed among {$totalUsers} users!");
    }
}
