<?php

use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->insert([
            'language' => 'en',
            'name' => 'English',
            'description' => 'This is english language',
        ]);

        DB::table('languages')->insert([
            'language' => 'np',
            'name' => 'Nepali',
            'description' => 'This is nepali language',
        ]);
    }
}
