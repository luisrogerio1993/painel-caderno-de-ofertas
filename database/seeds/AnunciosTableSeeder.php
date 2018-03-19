<?php

use Illuminate\Database\Seeder;
use App\Models\Anuncios;
use App\Models\Tipos_anuncio;
use App\Http\Controllers\Vendor;

class AnunciosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $nomes = ['Shampoo Seda 250ml', 
            'Carro 0Km', 
            'Moto semi nova', 
            'Casa beira mar', 
            'Audi A8', 
            'Audi A7', 
            'Audi A6', 
            'Condicionador Header Shoulder 350ml', 
            'Amostra - Hidratante para as mãos 50ml'];
        $quantTiposAnuncios = Tipos_anuncio::count();
        

        for ($i=0; $i<25; $i++) {
            Anuncios::create([
                'estabelecimento_id' => $i+1,
                'nome' => array_random($nomes),
                'user_id' => $i+1,
                'categoria_anuncio_id' => rand(1, 36),
                'descricao' => $faker->paragraph(1),
                'valor_atual' => Vendor::randomFloat(5, 10),
                'valor_original' => Vendor::randomFloat(11, 20),
                'anuncio_valido_ate' => $faker->dateTimeInInterval('now', '+7 days', 'America/Sao_Paulo'),
                'tipo_anuncio' => random_int(1, $quantTiposAnuncios),
            ]);
        }
    }
}