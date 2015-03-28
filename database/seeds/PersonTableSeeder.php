<?php 

use Illuminate\Database\Seeder;

class PersonTableSeeder extends Seeder {
    
    public function run() {
        \DB::table('people')->delete();

        $master_id = \DB::table('configs')->where('name', 'master')->first(['id'])->id;

        $now_str = (new \DateTime)->format('Y-m-d H:i:s');

        \DB::table('people')->insert([
            [
                'config_id'   => $master_id,
                'name'        => 'Everyone',
                'tf2items_id' => '*',
                'created_at'  => $now_str,
                'updated_at'  => $now_str
            ],
            [
                'config_id'   => $master_id,
                'name'        => 'Bumble',
                'tf2items_id' => 'STEAM_0:0:18322085',
                'created_at'  => $now_str,
                'updated_at'  => $now_str
            ],
            [
                'config_id'   => $master_id,
                'name'        => 'Lancil',
                'tf2items_id' => 'STEAM_0:0:11531014',
                'created_at'  => $now_str,
                'updated_at'  => $now_str
            ],
            [
                'config_id'   => $master_id,
                'name'        => 'Roker',
                'tf2items_id' => 'STEAM_0:1:19354576',
                'created_at'  => $now_str,
                'updated_at'  => $now_str
            ],
            [
                'config_id'   => $master_id,
                'name'        => 'WhiteThunder',
                'tf2items_id' => 'STEAM_0:0:54985507',
                'created_at'  => $now_str,
                'updated_at'  => $now_str
            ],
            [
                'config_id'   => $master_id,
                'name'        => 'Praynspray',
                'tf2items_id' => 'STEAM_0:1:54499221',
                'created_at'  => $now_str,
                'updated_at'  => $now_str
            ],
            [
                'config_id'   => $master_id,
                'name'        => 'Bumble+Lancil+Roker+WhiteThunder+Praynspray',
                'tf2items_id' => 'STEAM_0:0:18322085 ; STEAM_0:0:11531014 ; STEAM_0:1:19354576 ; STEAM_0:0:54985507 ; STEAM_0:1:54499221',
                'created_at'  => $now_str,
                'updated_at'  => $now_str
            ],
        ]);
    }
}