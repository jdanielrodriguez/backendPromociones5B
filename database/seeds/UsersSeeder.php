<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('roles')->insert([
            'id'                => 1,
            'nombre'            => 'Cliente Limitado',
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);
        DB::table('roles')->insert([
            'id'                => 2,
            'nombre'            => 'Cliente',
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('roles')->insert([
            'id'                => 3,
            'nombre'            => 'SubProveedor',
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('roles')->insert([
            'id'                => 4,
            'nombre'            => 'Proveedor',
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('roles')->insert([
            'id'                => 5,
            'nombre'            => 'Administrador',
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_compras')->insert([
            'id'                => 1,
            'nombre'            => 'Credito',
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_compras')->insert([
            'id'                => 2,
            'nombre'            => 'Contado',
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_ventas')->insert([
            'id'                => 1,
            'nombre'            => 'Credito',
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_ventas')->insert([
            'id'                => 2,
            'nombre'            => 'Contado',
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_ventas')->insert([
            'id'                => 3,
            'nombre'            => 'Pedido',
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_ventas')->insert([
            'id'                => 4,
            'nombre'            => 'Generado',
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_ventas')->insert([
            'id'                => 5,
            'nombre'            => 'Error',
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_formas_pago')->insert([
            'id'                => 1,
            'nombre'            => 'Tarjeta de Credito',
            'descripcion'       => 'Tarjetas de Credito y Debito',
            'css'               => null,
            'portada'           => 'https://icons.iconarchive.com/icons/aha-soft/business/256/credit-cards-icon.png',
            'color'             => null,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_formas_pago')->insert([
            'id'                => 2,
            'nombre'            => 'Paypal',
            'descripcion'       => 'Cuenta de Paypal',
            'css'               => null,
            'portada'           => 'https://ordenes.online/assets/images/formas-pago/Paypal.png',
            'color'             => null,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_formas_pago')->insert([
            'id'                => 3,
            'nombre'            => 'Deposito',
            'descripcion'       => 'Deposito a cuenta Bancaria',
            'css'               => null,
            'portada'           => null,
            'color'             => null,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_configuraciones')->insert([
            'id'                => 1,
            'nombre'            => 'Encabezado',
            'descripcion'       => 'Configuracion de Encabezado',
            'portada'           => 'https://toppng.com/uploads/preview/painting-icon-art-gallery-icon-11553392499462866ojse.png',
            'color'             => base64_encode('rgb(255,255,255) linear-gradient(90deg, rgba(255,255,255,1) 0%, rgba(232,186,162,1) 10%, rgba(200,91,34,1) 90%, rgba(255,255,255,1) 100%)'),
            'css'               => base64_encode('background: rgb(255,255,255) linear-gradient(90deg, rgba(255,255,255,1) 0%, rgba(232,186,162,1) 10%, rgba(200,91,34,1) 90%, rgba(255,255,255,1) 100%)'),
            'opciones'          => base64_encode('{mostrar:1,default:0}'),
            'estado'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_configuraciones')->insert([
            'id'                => 2,
            'nombre'            => 'Correo',
            'descripcion'       => 'Configuracion de Correo',
            'portada'           => null,
            'color'             => base64_encode('rgba(200,91,34,1)'),
            'css'               => base64_encode('rgba(200,91,34,1)'),
            'opciones'          => base64_encode('{mostrar:1,default:0}'),
            'estado'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_items')->insert([
            'id'                => 1,
            'nombre'            => 'Producto',
            'descripcion'       => 'Productos Fisicos',
            'portada'           => null,
            'color'             => base64_encode('rgba(200,91,34,1)'),
            'css'               => base64_encode('rgba(200,91,34,1)'),
            'opciones'          => base64_encode('{mostrar:1,default:0}'),
            'estado'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_items')->insert([
            'id'                => 2,
            'nombre'            => 'Servicios',
            'descripcion'       => 'Servicios Remotos o presenciales',
            'portada'           => null,
            'color'             => base64_encode('rgba(200,91,34,1)'),
            'css'               => base64_encode('rgba(200,91,34,1)'),
            'opciones'          => base64_encode('{mostrar:1,default:0}'),
            'estado'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_items')->insert([
            'id'                => 3,
            'nombre'            => 'Comida',
            'descripcion'       => 'Toda Clase de comida',
            'portada'           => null,
            'color'             => base64_encode('rgba(200,91,34,1)'),
            'css'               => base64_encode('rgba(200,91,34,1)'),
            'opciones'          => base64_encode('{mostrar:1,default:0}'),
            'estado'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_items')->insert([
            'id'                => 4,
            'nombre'            => 'Tickets',
            'descripcion'       => 'Tickets para eventos',
            'portada'           => null,
            'color'             => base64_encode('rgba(200,91,34,1)'),
            'css'               => base64_encode('rgba(200,91,34,1)'),
            'opciones'          => base64_encode('{mostrar:1,default:0}'),
            'estado'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_transacciones')->insert([
            'id'                => 1,
            'nombre'            => 'Adquisicion',
            'op'                => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_transacciones')->insert([
            'id'                => 2,
            'nombre'            => 'Debito',
            'op'                => 2,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_transacciones')->insert([
            'id'                => 3,
            'nombre'            => 'Credito',
            'op'                => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_transacciones')->insert([
            'id'                => 4,
            'nombre'            => 'Retiro',
            'op'                => 2,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_transacciones')->insert([
            'id'                => 5,
            'nombre'            => 'Recarga',
            'op'                => 2,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('pasarelas')->insert([
            'id'                => 1,
            'nombre'            => 'QPAYPRO',
            'logo'              => 'assets/images/formas-pago/Qpayp.png',
            'cambio'            => 7.98,
            'porcentaje'        => 5.5,
            'plus'              => 0.25,
            'estado'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('pasarelas')->insert([
            'id'                => 2,
            'nombre'            => '2CO',
            'logo'              => 'assets/images/formas-pago/2co.png',
            'cambio'            => 7.98,
            'porcentaje'        => 3.5,
            'plus'              => 0.35,
            'estado'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('pasarelas')->insert([
            'id'                => 3,
            'nombre'            => 'Pagalo',
            'logo'              => 'assets/images/formas-pago/Pagalo.png',
            'cambio'            => 7.98,
            'porcentaje'        => 6.5,
            'plus'              => 0.25,
            'estado'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('pasarelas')->insert([
            'id'                => 4,
            'nombre'            => 'Paypal',
            'logo'              => 'assets/images/formas-pago/Paypal.png',
            'cambio'            => 7.98,
            'porcentaje'        => 8.5,
            'plus'              => 0.25,
            'estado'            => 0,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('pasarelas')->insert([
            'id'                => 5,
            'nombre'            => 'Bac',
            'logo'              => 'assets/images/formas-pago/Paypal.png',
            'cambio'            => 7.98,
            'porcentaje'        => 8.5,
            'plus'              => 0.25,
            'estado'            => 0,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('comisiones')->insert([
            'minimo'            => 0,
            'maximo'            => 100,
            'cambio'            => 7.98,
            'porcentaje'        => 1.5,
            'plus'              => 0.25,
            'tipo'              => 1,
            'estado'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('comisiones')->insert([
            'minimo'            => 100,
            'maximo'            => 1000,
            'cambio'            => 7.98,
            'porcentaje'        => 2.75,
            'plus'              => 1.50,
            'tipo'              => 1,
            'estado'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('comisiones')->insert([
            'minimo'            => 1000,
            'maximo'            => 10000,
            'cambio'            => 7.98,
            'porcentaje'        => 4.5,
            'plus'              => 2.50,
            'tipo'              => 1,
            'estado'            => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('comisiones')->insert([
            'minimo'            => 0,
            'maximo'            => 100,
            'cambio'            => 7.98,
            'porcentaje'        => 3.6,
            'plus'              => 1.25,
            'estado'            => 1,
            'tipo'              => 2,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('comisiones')->insert([
            'minimo'            => 100,
            'maximo'            => 1000,
            'cambio'            => 7.98,
            'porcentaje'        => 2.5,
            'plus'              => 0.75,
            'estado'            => 1,
            'tipo'              => 2,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('comisiones')->insert([
            'minimo'            => 1000,
            'maximo'            => 10000,
            'cambio'            => 7.98,
            'porcentaje'        => 1.5,
            'plus'              => 0.25,
            'estado'            => 1,
            'tipo'              => 2,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('comisiones')->insert([
            'minimo'            => 10000,
            'maximo'            => 1000000,
            'cambio'            => 7.98,
            'porcentaje'        => 0,
            'plus'              => 5,
            'estado'            => 1,
            'tipo'              => 2,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('comisiones')->insert([
            'minimo'            => 0,
            'maximo'            => 100,
            'cambio'            => 7.98,
            'porcentaje'        => 0.5,
            'plus'              => 0.25,
            'tipo'              => 1,
            'estado'            => 0,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('comisiones')->insert([
            'minimo'            => 100,
            'maximo'            => 1000,
            'cambio'            => 7.98,
            'porcentaje'        => 1.75,
            'plus'              => 1.50,
            'tipo'              => 1,
            'estado'            => 0,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('comisiones')->insert([
            'minimo'            => 1000,
            'maximo'            => 10000,
            'cambio'            => 7.98,
            'porcentaje'        => 3.2,
            'plus'              => 2.50,
            'tipo'              => 1,
            'estado'            => 0,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('comisiones')->insert([
            'minimo'            => 0,
            'maximo'            => 100,
            'cambio'            => 7.98,
            'porcentaje'        => 2.5,
            'plus'              => 0.75,
            'estado'            => 0,
            'tipo'              => 2,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('comisiones')->insert([
            'minimo'            => 100,
            'maximo'            => 1000,
            'cambio'            => 7.98,
            'porcentaje'        => 1.5,
            'plus'              => 0.25,
            'estado'            => 0,
            'tipo'              => 2,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('comisiones')->insert([
            'minimo'            => 1000,
            'maximo'            => 10000,
            'cambio'            => 7.98,
            'porcentaje'        => 0.5,
            'plus'              => 0.15,
            'estado'            => 0,
            'tipo'              => 2,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('comisiones')->insert([
            'minimo'            => 10000,
            'maximo'            => 1000000,
            'cambio'            => 7.98,
            'porcentaje'        => 0,
            'plus'              => 2,
            'estado'            => 0,
            'tipo'              => 2,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('bancos')->insert([
            'nombre'            => "Banco Industrial",
            'dim'               => "BI",
            'estado'            => 1,
            'direccion'         => "Central",
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('bancos')->insert([
            'nombre'            => "Banco Agromercantil",
            'dim'               => "BAM",
            'estado'            => 1,
            'direccion'         => "Central",
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('bancos')->insert([
            'nombre'            => "Banco de los Trabajadores",
            'dim'               => "BANTRAB",
            'estado'            => 1,
            'direccion'         => "Central",
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_cuentas')->insert([
            'nombre'            => "Monetaria",
            'tipo'              => "MONETARIA",
            'estado'            => 1,
            'codigo'            => "1",
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('tipos_cuentas')->insert([
            'nombre'            => "Ahorros",
            'tipo'              => "AHORROS",
            'estado'            => 1,
            'codigo'            => "1",
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('users')->insert([
            "id"                => 1,
            'username'          =>  "admin",
            'password'          => bcrypt('admin'),
            'email'             => "contacto@josedanielrodriguez.com",
            'nombre'           => "System",
            'nacimiento'        => '{"day":6,"year":1995,"month":1}',
            'estado'            => 1,
            'rol'               => 5,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

    }
}