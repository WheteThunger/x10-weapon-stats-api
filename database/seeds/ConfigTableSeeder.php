<?php 

use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder {
    
    public function run() {
        \DB::table('configs')->delete();
        
        $now_str = (new \DateTime)->format('Y-m-d H:i:s');
        
        \DB::table('configs')->insert([
            ['name' => 'master', 'created_at' => $now_str, 'updated_at' => $now_str]
        ]);
    }
    
}