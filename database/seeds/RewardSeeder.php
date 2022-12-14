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
        $avaliableSherwinWilliams25 = 350;
        DB::table('reward')->insert([
            'id'                => 1,
            'name'              => 'Sherwin Williams.',
            'description'       => 'Descuento de Q25',
            'lan'               => 'es',
            'link'              => null,
            'img'               => 'https://promociones5b.com/backend/public/premios/',
            'avaliable'         => $avaliableSherwinWilliams25,
            'user'              => null,
            'use_code'          => true,
            'status'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);
        $totalSherwin = 0;
        for ($i = 0; $i < $avaliableSherwinWilliams25; $i++) {
            $totalSherwin++;
            $uuid = str_pad($totalSherwin, 6, "0", STR_PAD_LEFT);
            DB::table('opportunity')->insert([
                'id'                => $totalSherwin,
                'random_position'   => rand(0, $avaliable * 4),
                'points'            => 25,
                'avaliable'         => 1,
                'department'        => null,
                'code'              => $uuid,
                'reward'            => 1,
                'status'            => 1,
                'created_at'        => date('Y-m-d H:m:s'),
                'updated_at'        => $datetime->format('Y-m-d H:i:s')
            ]);
        }

        $avaliableSherwinWilliams35 = 200;
        DB::table('reward')->insert([
            'id'                => 2,
            'name'              => 'Sherwin Williams.',
            'description'       => 'Descuento de Q35',
            'lan'               => 'es',
            'link'              => null,
            'img'               => 'https://promociones5b.com/backend/public/premios/',
            'avaliable'         => $avaliableSherwinWilliams35,
            'use_code'          => true,
            'user'              => null,
            'status'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        for ($i = 0; $i < $avaliableSherwinWilliams35; $i++) {
            $totalSherwin++;
            $uuid = str_pad($totalSherwin, 6, "0", STR_PAD_LEFT);
            DB::table('opportunity')->insert([
                'id'                => $totalSherwin,
                'random_position'   => rand(0, $avaliable * 4),
                'points'            => 35,
                'avaliable'         => 1,
                'code'              => $uuid,
                'department'        => null,
                'reward'            => 2,
                'status'            => 1,
                'created_at'        => date('Y-m-d H:m:s'),
                'updated_at'        => $datetime->format('Y-m-d H:i:s')
            ]);
        }
        DB::table('reward')->insert([
            'id'                => 3,
            'name'              => 'Repechaje',
            'description'       => 'Repechaje',
            'lan'               => 'es',
            'img'               => 'https://promociones5b.com/backend/public/premios/repechaje.jpg',
            'avaliable'         => 24,
            'use_code'          => false,
            'user'              => null,
            'repechaje'         => 1,
            'status'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);


        for ($i = 0; $i < 24; $i++) {
            DB::table('opportunity')->insert([
                'random_position'   => rand($avaliable * 4, ($avaliable * 2) * 4),
                'points'            => 1000,
                'avaliable'         => 1,
                'department'        => null,
                'reward'            => 3,
                'status'            => 1,
                'repechaje'            => 1,
                'created_at'        => date('Y-m-d H:m:s'),
                'updated_at'        => $datetime->format('Y-m-d H:i:s')
            ]);
        }

        $avaliableDagas = 799;
        DB::table('reward')->insert([
            'id'                => 4,
            'name'              => 'DaGas.',
            'description'       => 'DaGas',
            'lan'               => 'es',
            'link'              => null,
            'img'               => 'https://promociones5b.com/backend/public/premios/da-gas/',
            'avaliable'         => $avaliableDagas,
            'user'              => null,
            'use_code'          => true,
            'status'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        for ($i = 0; $i < $avaliableDagas; $i++) {
            DB::table('opportunity')->insert([
                'random_position'   => rand(0, $avaliable * 4),
                'points'            => 50,
                'avaliable'         => 1,
                'department'        => null,
                'img'               => 'cupon_' . ($i + 1) . '.jpg',
                'code'              => ($i + 1),
                'reward'            => 4,
                'status'            => 1,
                'created_at'        => date('Y-m-d H:m:s'),
                'updated_at'        => $datetime->format('Y-m-d H:i:s')
            ]);
        }

        $dataTacoBell = [
            '6E4MFA',
            'YKTJ9G',
            'THPQTX',
            'GYNHEJ',
            '4P3GKN',
            'JY4M9X',
            'C3YQPE',
            'AC6T36',
            'QKCWGX',
            'CY4YM3',
            'XTCHNM',
            'WGF3MK',
            '3JF3AE',
            '9MCHXK',
            'Q396WX',
            'CHJ4YT',
            'JH3EYM',
            'AXT44Y',
            'J9644X',
            'M4WJCW',
            '9GWXNG',
            '4K9PHK',
            'JHNKHY',
            'QAKGF3',
            '3PFQCE',
            '4HQ33Q',
            'FQQW3P',
            'EYETP3',
            'N4H4YC',
            'Q9NFEG',
            'M96KWE',
            '4WAW3A',
            'PA3M4P',
            'NPEXJP',
            'EM3EFQ',
            'PMC4CP',
            'E4FAK3',
            '469GWG',
            '6CQQJ4',
            'EP4FQT',
            'JYJWH4',
            'WXCYWN',
            'PAAJTY',
            'EYKJM4',
            'Y4WM6Y',
            'PQW6PA',
            'PXQ3Y6',
            'C6GJCK',
            'PPNP6T',
            '6TGH3J',
            'ATMP3H',
            'Y439XN',
            'YAA4MH',
            'CYJAPH',
            'MF9YY3',
            '9NJK66',
            'AWENHW',
            'MFQJHK',
            'WMFFAF',
            '4KK33H',
            'TKNGYW',
            'WEXAX3',
            '6QXW6C',
            'H4JHK3',
            'N3Q9TK',
            '4YEPW6',
            'JWAA34',
            'FP69TK',
            '9MFW46',
            'T4CCC9',
            'QWW3GW',
            'XFFFTJ',
            'JTAW3T',
            'FFE3MK',
            'HAKNHQ',
            'MFX9GK',
            'YWEYYA',
            'MNGT94',
            '6EKCQM',
            'XJGA46',
            'QJQYXE',
            'FC4M6N',
            'N39PQP',
            'X4AFWA',
            'EFFEXA',
            'TMQE4X',
            '6PN9TE',
            'KYPP4K',
            'NAHTHM',
            'PKY3AW',
            'EG9E9Q',
            'HC6TYP',
            '6NXQNA',
            'PFYCXE',
            'QFXG93',
            'FF93Q6',
            'G6KHYF',
            'P6CYEJ',
            'QYKYHG',
            'GC93AW',
            'Y64WJC',
            'WPF6KW',
            'KMFPE4',
            '9HCY4J',
            'CGACQC',
            'TKF96C',
            'ATGPPJ',
            '4NEQQA',
            'JN6WY9',
            '9QFWGP',
            'JGQAQA',
            'FMF6TT',
            '4CKWEG',
            'FKNFWW',
            'X6HYWQ',
            '4Q3JKY',
            'AC63PY',
            '4QHJ3W',
            '44GXJM',
            'T3HQ3H',
            'KWQC43',
            'TJKEJT',
            'PGG3AK',
            '3YNFXG',
            'YJQWE3',
            'GNE66K',
            'MWX6KH',
            'CXM9YH',
            'JE99PT',
            'WM93GK',
            'QMQ4FW',
            'XAAMKQ',
            '4XKJW3',
            'W4K96J',
            'CWNY49',
            '39F36P',
            'FKKKPY',
            'JFWMNF',
            'XMCQ3C',
            'KAEF6X',
            'M4663K',
            'F969YQ',
            'NAQCPE',
            'NPYE4N',
            'N4J6ME',
            'XKAFYJ',
            'WKTG3J',
            '3GW6WP',
            'FPPC4Q',
            'FC9M39',
            '4AYPCW',
            'KYNQEY',
            'TCH4WH',
            'GX4WWG',
            'KPNPQP',
            'Y9F4JX',
            'G9TW6Q',
            'H94GEM',
            'JM6FMT',
            'WA6APT',
            '44MKW3',
            'J6GKG4',
            'PEMN63',
            'J6GCFY',
            'EMWPPM',
            'HXYGJA',
            'HXKTTM',
            'TPFAEE',
            'TA6QWY',
            'PNKPFY',
            'APTHTQ',
            '3QCGQF',
            'PC6EPP',
            'QXM3M6',
            'WGHGPF',
            'K3XYXC',
            'TQ6GA6',
            'GPJHJQ',
            'T6FXMH',
            'XHQH4Q',
            'Y3QHHJ',
            '4M3JEP',
            '6FWEC3',
            'QG9WM4',
            '4G3HWE',
            'HTQAGG',
            'NAGQEW',
            'HH9TXK',
            '6H9ANN',
            'TG996H',
            'X4KHYX',
            'FE9KMM',
            'E4Y9G9',
            'XWKY3W',
            'X964FX',
            'GM9MFT',
            'NWMW4A',
            'FXAENT',
            '33AGAJ',
            'YFW9XN',
            'HEWAFY',
            'KHHEMN',
            'NY64F3',
            'JFKAGH',
            'HYFMFG',
            '3APHT9',
            'AKHNGG',
            '4FCJFM',
            '6MW4JT',
            'Y4PP66',
            'KMPY9T',
            'JGFGMH',
            'XQPKNX',
            '3PMYFX',
            '6X3GFJ',
            'YT9FGT',
            'WXQPPE',
            'G4YCM4',
            'PGFAPW',
            'CF9A4M',
            'WMCNWH',
            'APQTT3',
            '3EMF9J',
            'ENMQTG',
            'KXMKMT',
            '3HGH4N',
            '3XE3QH',
            '99A4XH',
            'TCJQAC',
            'QKJEYG',
            'MYKJMY',
            'J9G3XT',
            'EC6EMG',
            'MP34YE',
            'KPCH69',
            'QPT4CN',
            'QAPMAG',
            '9K9WYF',
            'NK3HGY',
            'YNEMAA',
            '9GTPNK',
            'TJCPT3',
            'C4X6NG',
            'C9JKF6',
            'X93WK6',
            '64YTE9',
            'FYHHTA',
            'QPTMCT',
            '6MFWHC',
            '3QPN9T',
            'E9F4HJ',
            'YYHANF',
            'AKXH4N',
            'FX36MJ',
            'CXMKTP',
            '6YETPF',
            '4GGMGC',
            'NMG3TJ',
            'TT3G9N',
            'MQGCQJ',
            '9QTCYG',
            '3QGW4C',
            'FHJWFT',
            'AMTHW3',
            '4TKWKQ',
            'FNQCHQ',
            'JATPYE',
            'NMETPJ',
            'NE3QEA',
            'HQXATA',
            'YGANXX',
            '9GMNPQ',
            'XNQHF6',
            'MKJKHN',
            'QACEGM',
            'YPEGPW'
        ];
        $avaliableTacoBell = count($dataTacoBell);
        DB::table('reward')->insert([
            'id'                => 5,
            'name'              => 'Taco Bell.',
            'description'       => 'Codigo de descuento',
            'lan'               => 'es',
            'link'              => null,
            'img'               => 'https://promociones5b.com/backend/public/premios/tacobell/',
            'avaliable'         => $avaliableTacoBell,
            'use_code'          => true,
            'user'              => null,
            'status'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        foreach ($dataTacoBell as $key => $value) {
            DB::table('opportunity')->insert([
                'random_position'   => rand(0, $avaliable * 4),
                'points'            => 50,
                'avaliable'         => 1,
                'department'        => null,
                'img'               => 'cupon_' . $value . '.png',
                'code'              => $value,
                'reward'            => 5,
                'status'            => 1,
                'created_at'        => date('Y-m-d H:m:s'),
                'updated_at'        => $datetime->format('Y-m-d H:i:s')
            ]);
        }

        $avaliablePromerica = 8;
        DB::table('reward')->insert([
            'id'                => 6,
            'name'              => 'Promerica.',
            'description'       => 'Promerica',
            'lan'               => 'es',
            'link'              => null,
            'img'               => 'https://promociones5b.com/backend/public/premios/promerica/',
            'avaliable'         => $avaliablePromerica,
            'user'              => null,
            'use_code'          => true,
            'status'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        for ($i = 0; $i < $avaliablePromerica; $i++) {
            $uuid = uniqid();
            DB::table('opportunity')->insert([
                'random_position'   => rand(0, $avaliable * 4),
                'points'            => 25,
                'avaliable'         => 1,
                'department'        => null,
                'img'               => 'cupon_' . $uuid . '.png',
                'code'              => $uuid,
                'reward'            => 6,
                'status'            => 1,
                'created_at'        => date('Y-m-d H:m:s'),
                'updated_at'        => $datetime->format('Y-m-d H:i:s')
            ]);
        }



        $avaliableCampero = 1180;
        $serie = 1;
        DB::table('reward')->insert([
            'id'                => 7,
            'name'              => 'Campero.',
            'description'       => 'Campero',
            'lan'               => 'es',
            'link'              => null,
            'img'               => 'https://promociones5b.com/backend/public/premios/campero/',
            'avaliable'         => $avaliableCampero,
            'user'              => null,
            'use_code'          => true,
            'status'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        for ($i = 0; $i < $avaliableCampero; $i++) {
            $uuid = "D04" . str_pad($serie, 6, "0", STR_PAD_LEFT);
            $total = $this->getCamperoValue($serie);
            DB::table('opportunity')->insert([
                'random_position'   => rand(0, $avaliable * 4),
                'points'            => $total,
                'avaliable'         => 1,
                'department'        => null,
                'img'               => 'cupon_' . $uuid . '.png',
                'code'              => $uuid,
                'reward'            => 7,
                'status'            => 1,
                'created_at'        => date('Y-m-d H:m:s'),
                'updated_at'        => $datetime->format('Y-m-d H:i:s')
            ]);
            $serie++;
        }
    }

    private function getCamperoValue($index){
        if($index >= 1 && $index <= 472) return 5;
        if($index >= 471 && $index <= 767) return 10;
        if($index >= 766 && $index <= 1003) return 15;
        if($index >= 1002 && $index <= 1062) return 20;
        if($index >= 1061 && $index <= 1180) return 25;
    }
}
