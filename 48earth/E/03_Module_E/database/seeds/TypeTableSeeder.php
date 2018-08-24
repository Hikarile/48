<?php

use Illuminate\Database\Seeder;
use App\Type;

class TypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'name' => '區間列車',
                'speed' => 50,
            ],
            [
                'name' => '快速列車',
                'speed' => 300,
            ],
            [
                'name' => '磁浮列車',
                'speed' => 500,
            ],
        ];
        Type::insert($types);
    }
}
