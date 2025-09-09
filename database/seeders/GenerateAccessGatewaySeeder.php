<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Actions\AccessGateway\GenerateAccessGatewayAction;

class GenerateAccessGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        dd((new GenerateAccessGatewayAction)->execute());
    }
}
