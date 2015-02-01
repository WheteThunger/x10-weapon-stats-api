<?php 

use Illuminate\Database\Seeder;

class PersonTableSeeder extends Seeder {
    
    public function run() {
        \DB::table('people')->delete();
        
        $now_str = (new \DateTime)->format('Y-m-d H:i:s');
        
        \DB::table('people')->insert([
            [
                'name'        => 'Everyone',
                'tf2items_id' => '*',
                'created_at'  => $now_str,
                'updated_at'  => $now_str
            ],
            [
                'name'        => 'Bumble',
                'tf2items_id' => 'STEAM_0:0:18322085',
                'created_at'  => $now_str,
                'updated_at'  => $now_str
            ],
            [
                'name'        => 'Lancil',
                'tf2items_id' => 'STEAM_0:0:11531014',
                'created_at'  => $now_str,
                'updated_at'  => $now_str
            ],
            [
                'name'        => 'Roker',
                'tf2items_id' => 'STEAM_0:1:19354576',
                'created_at'  => $now_str,
                'updated_at'  => $now_str
            ],
            [
                'name'        => 'WhiteThunder',
                'tf2items_id' => 'STEAM_0:0:54985507',
                'created_at'  => $now_str,
                'updated_at'  => $now_str
            ],
            [
                'name'        => 'Bumble+Lancil+Roker+WhiteThunder',
                'tf2items_id' => 'STEAM_0:0:18322085 ; STEAM_0:0:11531014 ; STEAM_0:1:19354576 ; STEAM_0:0:54985507',
                'created_at'  => $now_str,
                'updated_at'  => $now_str
            ],
        ]);
    }
}