<?php

use Illuminate\Database\Seeder;
use App\Station;

class StationsTableSeeder extends Seeder
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
                'chinese' => '台北',
                'english' => 'TAIPEI',
            ],
            [
                'chinese' => '桃園',
                'english' => 'TAOYUAN',
            ],
            [
                'chinese' => '新竹',
                'english' => 'HSINCHU',
            ],
            [
                'chinese' => '苗栗',
                'english' => 'MIAOLI',
            ],
            [
                'chinese' => '台中',
                'english' => 'TAICHUNG',
            ],
            [
                'chinese' => '彰化',
                'english' => 'CHANGHUA',
            ],
            [
                'chinese' => '雲林',
                'english' => 'YOULIN',
            ],
            [
                'chinese' => '嘉義',
                'english' => 'CHIAYI',
            ],
            [
                'chinese' => '台南',
                'english' => 'TAINAN',
            ],
            [
                'chinese' => '高雄',
                'english' => 'KAOHSIUNG',
            ],
            [
                'chinese' => '屏東',
                'english' => 'PINGTUNG',
            ],
            [
                'chinese' => '台東',
                'english' => 'TAITUNG',
            ],
            [
                'chinese' => '花蓮',
                'english' => 'HUALIEN',
            ],
            [
                'chinese' => '宜蘭',
                'english' => 'LIAN',
            ],
        ];
        Station::insert($data);
    }
}
