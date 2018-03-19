<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ListaUfsTableSeeder::class);
        $this->call(CategoriasEstabelecimentosTableSeeder::class);
        $this->call(TiposAnuncioTableSeeder::class);
        $this->call(ConfiguracoesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(EstabelecimentosTableSeeder::class);
        $this->call(AnunciosTableSeeder::class);
        $this->call(ProdutosLojaTableSeeder::class);
        $this->call(ComprasTableSeeder::class);
        $this->call(ListaProdutosComprasTableSeeder::class);
        $this->call(ClicksAnunciosTableSeeder::class);
        $this->call(PerguntasFrequentesTableSeeder::class);
        $this->call(ComentariosAnuncioTableSeeder::class);
    }
}
