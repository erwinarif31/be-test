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
        $divisionsName = [
            'Mobile Apps',
            'QA',
            'Full Stack',
            'Backend',
            'Frontend',
            'UI/UX Designer'
        ];


        foreach ($divisionsName as $name) {
            $division = new Division();
            $division->name = $name;
            $division->save();
        }
    }
}
