<?php

use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $avaliable = 10;

        DB::table('reward')->insert([
            'id'                => 1,
            'name'              => 'Menu de comida',
            'description'       => 'Menu de comida',
            'lan'               => 'es',
            'link'              => 'https://mcdonalds.com.gt/promociones-y-apps',
            'img'               => 'https://mcdonalds.com.gt/imagen/carousel-promociones/1666910200_010_AppCupones_Banner_1200x1200px_DesayunoMundialista_GT.jpg',
            'avaliable'         => $avaliable,
            'user'              => null,
            'status'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);
        for($i = 0; $i < $avaliable; $i++) {
            DB::table('opportunity')->insert([
                'random_position'   => $i + 1,
                'points'            => 100,
                'avaliable'         => 1,
                'department'        => null,
                'reward'            => 1,
                'status'            => 1,
                'created_at'        => date('Y-m-d H:m:s'),
                'updated_at'        => date('Y-m-d H:m:s')
            ]);
        }
    }
}
