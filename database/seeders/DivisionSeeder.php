<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisionsName = ['IT', 'HR', 'Marketing'];

        foreach ($divisionsName as $name) {
            $division = new Division();
            $division->name = $name;
            $division->save();
        }
    }
}
