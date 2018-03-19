<?php

use Illuminate\Database\Seeder;
use App\Models\Compras;

class ComprasTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $listaCodigosTrascasao = [
            'C3D010CF-0D8B-49A5-A5ED-1BA211788598',
            '188F87E4-C41C-4CDD-B79E-2CD5A8E41A3A',
            '82EA1870-6AD9-4CC6-8B42-E2E4043293EA',
            '34E58B05-38DB-4300-B317-E4EE642D50A8',
            '82EA1870-6AD9-4CC6-8B42-E2E4043293EB',
        ];
        
        $listaCodigosReferencias = [
            '201802130325485a8276dc0c1a8',
            '201802130326505a82771abc524',
            '20180213032sd69f46sd4fsd65f',
            '2018021303265ds4f6d5s4fd6sd',
            '20180213032654564dsf65sd4fs',
        ];
        for($i = 0; $i < 5; $i++){
            Compras::create([
                'user_id' => 1,
                'referencia' => $listaCodigosReferencias[$i],
                'codigo_transacao' => $listaCodigosTrascasao[$i],
                'status' => rand(1, 3),
                'metodo_pagamento' => 2,
            ]);
        }
    }
}
