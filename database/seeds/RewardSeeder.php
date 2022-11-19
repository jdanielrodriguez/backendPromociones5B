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
        $datetime = new DateTime(date('Y-m-d H:m:s'));
        $datetime->modify('-2 week');
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

        for ($i = 0; $i < $avaliable; $i++) {
            DB::table('opportunity')->insert([
                'random_position'   => rand(0, $avaliable * 4),
                'points'            => 100,
                'avaliable'         => 1,
                'department'        => null,
                'reward'            => 1,
                'status'            => 1,
                'created_at'        => date('Y-m-d H:m:s'),
                'updated_at'        => $datetime->format('Y-m-d H:i:s')
            ]);
        }
        DB::table('reward')->insert([
            'id'                => 2,
            'name'              => 'Repechaje',
            'description'       => 'Repechaje',
            'lan'               => 'es',
            'avaliable'         => $avaliable,
            'user'              => null,
            'repechaje'         => 1,
            'status'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        
        for ($i = 0; $i < $avaliable; $i++) {
            DB::table('opportunity')->insert([
                'random_position'   => rand($avaliable * 4, ($avaliable * 2) * 4),
                'points'            => 1000,
                'avaliable'         => 1,
                'department'        => null,
                'reward'            => 2,
                'status'            => 1,
                'repechaje'            => 1,
                'created_at'        => date('Y-m-d H:m:s'),
                'updated_at'        => $datetime->format('Y-m-d H:i:s')
            ]);
        }
    }
}
