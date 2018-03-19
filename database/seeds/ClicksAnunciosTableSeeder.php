<?php

use Illuminate\Database\Seeder;
use App\Models\Clicks_anuncios;
use App\Http\Controllers\Vendor;

class ClicksAnunciosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $tiposAnuncio = ['Padrão', 'Premium', 'Proximidade Física'];
        
        for ($i=1; $i<26; $i++) {
            for ($j=0; $j<3; $j++) {
                Clicks_anuncios::create([
                    'user_id' => $i,
                    'nome_usuario' => $faker->firstName,
                    'nome_tipo_anuncio' => $tiposAnuncio[array_rand($tiposAnuncio)],
                    'nome_anuncio' => $faker->firstName,
                    'nome_estabelecimento' => $faker->company,
                    'ip_click' => $faker->ipv4,
                    'valor_click' => Vendor::randomFloat(0.1, 0.5),
                ]);
            }
        }
    }
}