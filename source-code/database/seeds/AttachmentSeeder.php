<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attachment = [
            [
                'id' => 1,
                'filename' => 'default-avatar.jpg',
                'type' => 'User',
                'foreign_id' => 0,
                'path' => 'User/default-avatar.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]            
        ];

        DB::table('attachments')->insert($attachment);
    }
}
