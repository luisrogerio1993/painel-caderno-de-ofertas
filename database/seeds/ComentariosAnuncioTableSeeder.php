<?php

use Illuminate\Database\Seeder;
use App\Models\Comentarios_anuncios;

class ComentariosAnuncioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        
        for ($i = 0; $i < 50; $i++){
            Comentarios_anuncios::create([
                'id_anuncio' => rand(1, 25),
                'id_usuario' => rand(1, 25),
                'comentario' => $faker->text(1000),
                'quantidade_estrelas_avaliacao' => rand(1, 5),
            ]);
        }
    }
}
