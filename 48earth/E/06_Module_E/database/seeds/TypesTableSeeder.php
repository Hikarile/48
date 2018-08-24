<?php

use Illuminate\Database\Seeder;
use App\Type;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => '區間列車',
                'speed' => '50',
            ],
            [
                'name' => '快速列車',
                'speed' => '300',
            ],
            [
                'name' => '磁浮列車',
                'speed' => '500',
            ],
        ];
        Type::insert($data);
    }
}
