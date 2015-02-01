<?php 

use Illuminate\Database\Seeder;

class ClassTableSeeder extends Seeder {
    
    public function run() {
        \DB::table('classes')->delete();
        
        \DB::table('classes')->insert([
            ['name' => 'scout'],
            ['name' => 'soldier'],
            ['name' => 'pyro'],
            ['name' => 'demoman'],
            ['name' => 'heavy'],
            ['name' => 'engineer'],
            ['name' => 'medic'],
            ['name' => 'sniper'],
            ['name' => 'spy'],
        ]);
    }
    
}