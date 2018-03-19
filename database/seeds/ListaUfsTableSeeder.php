<?php

use Illuminate\Database\Seeder;
use App\Models\Lista_ufs;

class ListaUfsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listaUfs = [
            'AC',
            'AL',
            'AP',
            'AM',
            'BA',
            'CE',
            'DF',
            'ES',
            'GO',
            'MA',
            'MT',
            'MS',
            'MG',
            'PR',
            'PB',
            'PA',
            'PE',
            'PI',
            'RJ',
            'RN',
            'RS',
            'RO',
            'RR',
            'SC',
            'SE',
            'SP',
            'TO'];
        
        foreach ($listaUfs as $Uf){
            Lista_ufs::create([  'nome' =>  $Uf ]);
        }
    }
}