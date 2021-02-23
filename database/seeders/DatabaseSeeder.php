<?php

namespace Database\Seeders;

use App\Models\Advert;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        DB::unprepared(file_get_contents(__DIR__ . '/sql/user_status.sql'));
        DB::unprepared(file_get_contents(__DIR__ . '/sql/user_roles.sql'));
        \App\Models\User::factory(10)->create();
        DB::unprepared(file_get_contents(__DIR__ . '/sql/categories.sql'));
        DB::unprepared(file_get_contents(__DIR__ . '/sql/regions.sql'));
        DB::unprepared(file_get_contents(__DIR__ . '/sql/cities.sql'));
        DB::unprepared(file_get_contents(__DIR__ . '/sql/advert_status.sql'));

        DB::unprepared(file_get_contents(__DIR__ . '/sql/moderation_resolution.sql'));

        \App\Models\Advert::factory(100)->create();

        for ($photo_id = 0; $photo_id < 20; $photo_id++) {
            $url = "https://picsum.photos/id/{$photo_id}/200/300";
            $contents = file_get_contents($url);
            $name = "images/{$photo_id}jpg";
            Storage::put($name, $contents);
        }

        for ($advert_id = 1; $advert_id < Advert::count(); $advert_id++) {
            for ($photo_id = 0; $photo_id < rand(1, 10); $photo_id++) {
                Photo::create([
                    'advert_id' => $advert_id,
                    'is_main' => $photo_id == 0 ? 1 : 0,
                    'link' => 'images/' . rand(1, 20) . '.jpg',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $faker = \Faker\Factory::create('ru_RU');
        for ($i = 1; $i < Advert::count(); $i++) {
            if (Advert::find($i)->status->slug == 'rejected'
                || Advert::find($i)->status->slug == 'acive'
                || Advert::find($i)->status->slug == 'soldout') {
                for ($j = 0; $j = rand(0, 5); $j++) {
                    DB::table('moderations')->insert([
                        'moderated_at' => now(),
                        'advert_id' => $i,
                        'user_id' => User::all()->random()->id,
                        'resolution' => 'rejected',
                        'reason' => $faker->realText(256),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            if (Advert::find($i)->status->slug == 'approved'
                || Advert::find($i)->status->slug == 'soldout') {
                DB::table('moderations')->insert([
                    'moderated_at' => now(),
                    'advert_id' => $i,
                    'user_id' => User::all()->random()->id,
                    'resolution' => 'approved',
                    'reason' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

        }

    }
}
