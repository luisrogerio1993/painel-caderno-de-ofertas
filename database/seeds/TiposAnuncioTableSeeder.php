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
            'Padrão' => 'Anúncio com visibilidade padrão em nossa plataforma. Econômico, e funcional.',
            'Premium' => 'Anúncio com maior visibilidade, e posicionamento privilegiado. Sempre exibido primeiro em nosso App.',
            'Proximidade Física' => 'Anúncio com alerta de geolocalização. Quando alguém usando o App passar perto de sua loja, irá receber uma notificação deste anúncio. (Ele não será exibido no App).',
            ];
        
        foreach ($listaTipos as $tipo => $desc){
            Tipos_anuncio::create([
                'nome' =>  $tipo,
                'descricao' =>  $desc,
            ]);
        }
    }
}
