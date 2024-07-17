<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class UpdateSettingsSlugProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::create(['key' => 'slug_prefix','name' => 'Slug Prefix', 'value' => 'blitz-', 'description' => 'Slug Prefix is used when visiting supplier professional profile.']);
    }
}
