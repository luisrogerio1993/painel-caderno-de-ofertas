<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Http\Controllers\Vendor;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        User::create([
            'name' => 'Luis Araujo',
            'email' => 'lc05869693772161528862@sandbox.pagseguro.com.br',
            'password' => bcrypt('123456'),
            'cidade' => $faker->city,
            'uf' => rand(1, 27),
            'cep' => rand(55750000, 65750000),
            'is_admin' => '1',
            'email_token' => str_random(30),
            'credito_disponivel' => Vendor::randomFloat(2, 100),
            
        ]);
        
        for ($i=0; $i<24; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->email,
                'password' => bcrypt('123456'),
                'cidade' => $faker->city,
                'uf' => rand(1, 27),
                'cep' => rand(55750000, 65750000),
                'email_token' => str_random(30),
                'credito_disponivel' => Vendor::randomFloat(2, 100),
            ]);
        }
    }
}