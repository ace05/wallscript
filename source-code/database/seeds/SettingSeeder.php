<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'id' => 1,
                'setting_category_id' => 1,
                'name' => 'Site Name',
                'trans_key' => 'site_name',
                'code' => 'db.site_name',
                'value' => 'Social Wall',
                'inputs' => null,
                'type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'setting_category_id' => 1,
                'name' => 'Logo Url',
                'trans_key' => 'logo_url',
                'code' => 'db.logo_url',
                'value' => '',
                'inputs' => null,
                'type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 3,
                'setting_category_id' => 1,
                'name' => 'Email Template Logo Url',
                'trans_key' => 'email_template_logo_url',
                'code' => 'db.email_logo_url',
                'value' => 'http://www.socialwallscript.com/wp-content/uploads/2016/09/SWS.png',
                'inputs' => null,
                'type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
    		[
                'id' => 4,
                'setting_category_id' => 2,
                'name' => 'Email Verification',
                'trans_key' => 'email_verification',
                'code' => 'db.email_verification',
                'value' => 'Yes',
                'inputs' => 'Yes,No',
                'type' => 'select',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 5,
                'setting_category_id' => 2,
                'name' => 'Login Using',
                'trans_key' => 'login_using',
                'code' => 'db.login_type',
                'value' => 'Username',
                'inputs' => 'Username,Email,Email or Username',
                'type' => 'select',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 6,
                'setting_category_id' => 2,
                'name' => 'Enable Facebook Login',
                'trans_key' => 'enable_facebook_login',
                'code' => 'db.facebook_login',
                'value' => 'No',
                'inputs' => 'Yes,No',
                'type' => 'select',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 7,
                'setting_category_id' => 2,
                'name' => 'Facebook App ID',
                'trans_key' => 'facebook_app_id',
                'code' => 'db.facebook_app_id',
                'value' => '',
                'inputs' => null,
                'type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 8,
                'setting_category_id' => 2,
                'name' => 'Facebook App Secret',
                'trans_key' => 'facebook_app_secret',
                'code' => 'db.facebook_app_secret',
                'value' => '',
                'inputs' => null,
                'type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 9,
                'setting_category_id' => 2,
                'name' => 'Enable LinkedIn Login',
                'trans_key' => 'enable_linkedin_login',
                'code' => 'db.linkedin_login',
                'value' => 'No',
                'inputs' => 'Yes,No',
                'type' => 'select',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 10,
                'setting_category_id' => 2,
                'name' => 'LinkedIn Client ID',
                'trans_key' => 'linkedin_client_id',
                'code' => 'db.linkedin_client_id',
                'value' => '',
                'inputs' => null,
                'type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 11,
                'setting_category_id' => 2,
                'name' => 'LinkedIn Client Secret',
                'trans_key' => 'linkedin_client_secret',
                'code' => 'db.linkedin_client_secret',
                'value' => '',
                'inputs' => null,
                'type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 12,
                'setting_category_id' => 2,
                'name' => 'Enable Google Login',
                'trans_key' => 'enable_google_login',
                'code' => 'db.google_login',
                'value' => 'No',
                'inputs' => 'Yes,No',
                'type' => 'select',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 13,
                'setting_category_id' => 2,
                'name' => 'Google Client ID',
                'trans_key' => 'google_client_id',
                'code' => 'db.google_client_id',
                'value' => '',
                'inputs' => null,
                'type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 14,
                'setting_category_id' => 2,
                'name' => 'Google Client Secret',
                'trans_key' => 'google_client_secret',
                'code' => 'db.google_client_secret',
                'value' => '',
                'inputs' => null,
                'type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 15,
                'setting_category_id' => 2,
                'name' => 'Google Map API Key',
                'trans_key' => 'google_map_api_key',
                'code' => 'db.google_map_api_key',
                'value' => '',
                'inputs' => null,
                'type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 16,
                'setting_category_id' => 3,
                'name' => 'Sidebar Left - Banner(217x450)',
                'trans_key' => 'sidebar_left_banner',
                'code' => 'db.sidebar_left_banner',
                'value' => '<img src="https://placehold.it/217x450" class="img-responsive" />',
                'inputs' => null,
                'type' => 'textarea',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 17,
                'setting_category_id' => 3,
                'name' => 'Sidebar Right - Banner(217x450)',
                'trans_key' => 'sidebar_right_banner',
                'code' => 'db.sidebar_right_banner',
                'value' => '<img src="https://placehold.it/245x245" class="img-responsive" />',
                'inputs' => null,
                'type' => 'textarea',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 18,
                'setting_category_id' => 1,
                'name' => 'Favicon Url',
                'trans_key' => 'favicon_url',
                'code' => 'db.favicon_url',
                'value' => '',
                'inputs' => null,
                'type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
    	];
        DB::table('settings')->insert($settings);
    }
}