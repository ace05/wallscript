<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SettingCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$categories = [
            [
                'id' => 1,
                'name' => 'General',
                'slug' => 'general',
                'icon' => 'fa fa-cog',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
    		[
                'id' => 2,
    		    'name' => 'Register and Login',
    		    'slug' => 'register-and-login',
                'icon' => 'fa fa-user-plus',
    		    'created_at' => Carbon::now(),
    		    'updated_at' => Carbon::now()
            ],
            [
                'id' => 3,
                'name' => 'Advertisement',
                'slug' => 'ads',
                'icon' => 'fa fa-adn',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
    	];
        DB::table('setting_categories')->insert($categories);
    }
}
