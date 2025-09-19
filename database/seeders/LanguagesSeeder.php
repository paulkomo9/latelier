<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'code'   => 'en',
                'name' => 'English',
                'native_name' => 'English',
                'flag' => '',
                'script_direction' => 'LTR',
                'float' => 'float-end',
                'lang_status' => 1,
                'deleted_at' => null,
            ],
            [
                'code'   => 'fr',
                'name' => 'French',
                'native_name' => 'Français',
                'flag' => '',
                'script_direction' => 'LTR',
                'float' => 'float-end',
                'lang_status' => 2,
                'deleted_at' => null,
            ],
            [
                'code'   => 'ar',
                'name' => 'Arabic',
                'native_name' => 'العربية',
                'flag' => '',
                'script_direction' => 'RTL',
                'float' => 'float-start',
                'lang_status' => 2,
                'deleted_at' => null,
            ],
            [
                'code'   => 'esp',
                'name' => 'Spanish',
                'native_name' => 'Español',
                'flag' => '',
                'script_direction' => 'LTR',
                'float' => 'float-end',
                'lang_status' => 2,
                'deleted_at' => null,
            ],

             [
                'code'   => 'it',
                'name' => 'Italian',
                'native_name' => 'Italiano',
                'flag' => '',
                'script_direction' => 'LTR',
                'float' => 'float-end',
                'lang_status' => 2,
                'deleted_at' => null,
            ],

            [
                'code'   => 'hi',
                'name' => 'Hindi',
                'native_name' => 'हिन्दी',
                'flag' => '',
                'script_direction' => 'LTR',
                'float' => 'float-end',
                'lang_status' => 2,
                'deleted_at' => null,
            ],
            [
                'code'   => 'sw',
                'name' => 'Swahili',
                'native_name' => 'Kiswahili',
                'flag' => '',
                'script_direction' => 'LTR',
                'float' => 'float-end',
                'lang_status' => 1,
                'deleted_at' => null,
            ],
            [
                'code'   => 'de',
                'name' => 'German',
                'native_name' => 'Deutsch',
                'flag' => '',
                'script_direction' => 'LTR',
                'float' => 'float-end',
                'lang_status' => 2,
                'deleted_at' => null,
            ],
            [
                'code'   => 'ru',
                'name' => 'Russian',
                'native_name' => 'Русский',
                'flag' => '',
                'script_direction' => 'LTR',
                'float' => 'float-end',
                'lang_status' => 2,
                'deleted_at' => null,
            ],
            [
                'code'   => 'sr',
                'name' => 'Serbian',
                'native_name' => 'Српски / Srpski',
                'flag' => '',
                'script_direction' => 'LTR',
                'float' => 'float-end',
                'lang_status' => 2,
                'deleted_at' => null,
            ],
            
           
        ];

        foreach ($languages as $language) {
            DB::table('languages')->updateOrInsert(
                ['code' => $language['code']],
                $language
            );
        }
    }
}
