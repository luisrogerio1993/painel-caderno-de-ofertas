<?php

use Illuminate\Database\Seeder;
use App\Models\Tipos_anuncio;

class TiposAnuncioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listaTipos = [
            'Padrão',
            'Premium',
            'Proximidade Física'];
        
        foreach ($listaTipos as $tipo){
            Tipos_anuncio::create([  'nome' =>  $tipo ]);
        }
    }
}
