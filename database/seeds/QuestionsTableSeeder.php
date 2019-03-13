<?php

use App\Option;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Step 1

        factory(App\Question::class)->create([
            'text'       => 'Nombres',
            'step_uuid'  => DB::table('steps')->whereOrder(1)->first()->uuid,
            'type'       => 'SIMPLE_TEXT',
            'order'      => 1,
            'is_profile' => true,
        ]);

        factory(App\Question::class)->create([
            'text'       => 'Apellidos',
            'step_uuid'  => DB::table('steps')->whereOrder(1)->first()->uuid,
            'type'       => 'SIMPLE_TEXT',
            'order'      => 2,
            'is_profile' => true,
        ]);
        factory(App\Question::class)->create([
            'text'       => 'Fecha de Nacimiento',
            'step_uuid'  => DB::table('steps')->whereOrder(1)->first()->uuid,
            'type'       => 'DATE',
            'order'      => 3,
            'is_profile' => true,
        ]);
        factory(App\Question::class)->create([
            'text'       => 'Genero',
            'step_uuid'  => DB::table('steps')->whereOrder(1)->first()->uuid,
            'order'      => 4,
            'is_profile' => true,
        ])->options()->saveMany(
            [
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Hombre']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Mujer']),
            ]
        );
        factory(App\Question::class)->create([
            'text'       => 'Número de DUI',
            'step_uuid'  => DB::table('steps')->whereOrder(1)->first()->uuid,
            'type'       => 'DUI',
            'order'      => 5,
            'is_profile' => true,
        ]);
        factory(App\Question::class)->create([
            'text'       => 'Número de NIT',
            'step_uuid'  => DB::table('steps')->whereOrder(1)->first()->uuid,
            'type'       => 'NIT',
            'order'      => 6,
            'is_profile' => true,
        ]);
        factory(App\Question::class)->create([
            'text'       => 'Dirección',
            'step_uuid'  => DB::table('steps')->whereOrder(1)->first()->uuid,
            'type'       => 'SIMPLE_TEXT',
            'order'      => 7,
            'is_profile' => true,
        ]);
        factory(App\Question::class)->create([
            'text'       => 'Departamento',
            'step_uuid'  => DB::table('steps')->whereOrder(1)->first()->uuid,
            'order'      => 8,
            'is_profile' => true,
        ])->options()->saveMany(
            [
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Sonsonate']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Ahuachapán']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Santa Ana']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Cabañas']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Chalatenango']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Cuscatlán']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'La Libertad']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'La Paz']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'San Salvador']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'San Vicente']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Morazán']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'San Miguel']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Usulután']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'La Unión']),
            ]
        );
        factory(App\Question::class)->create([
            'text'       => 'Correo Electrónico',
            'step_uuid'  => DB::table('steps')->whereOrder(1)->first()->uuid,
            'type'       => 'EMAIL',
            'order'      => 9,
            'is_profile' => true,
        ]);
        // factory(App\Question::class)->create([
        //     'text'       => 'Teléfono',
        //     'step_uuid'  => DB::table('steps')->whereOrder(1)->first()->uuid,
        //     'type'       => 'PHONE',
        //     'order'      => 10,
        //     'is_profile' => true,
        // ]);
        factory(App\Question::class)->create([
            'text'       => 'Por Favor ingresa un número alterno en caso no podamos contactarte al teléfono registrado',
            'step_uuid'  => DB::table('steps')->whereOrder(1)->first()->uuid,
            'type'       => 'PHONE',
            'order'      => 10,
            'is_profile' => true,
        ]);

        // Step 2
        factory(App\Question::class)->create([
            'text'      => '¿Es tuyo este dispositivo?',
            'step_uuid' => DB::table('steps')->whereOrder(2)->first()->uuid,
            'order'     => 1,
        ])->options()->saveMany(
            [
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Si, es mio']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'No, pertenece a alguien más']),
            ]
        );

        factory(App\Question::class)->create([
            'text'      => '¿Cuanto tiempo llevas usando este dispositivo?',
            'step_uuid' => DB::table('steps')->whereOrder(2)->first()->uuid,
            'order'     => 2,
        ])->options()->saveMany(
            [
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Menos de 1 mes']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => '1-6 meses']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => '6-12 meses']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => '+1 Año']),
            ]
        );

        factory(App\Question::class)->create([
            'text'      => '¿Como conociste Diimo?',
            'step_uuid' => DB::table('steps')->whereOrder(2)->first()->uuid,
            'order'     => 3,
        ])->options()->saveMany(
            [
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Amigos']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Facebook']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Radio']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Google Play Store']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Otros']),
            ]
        );

        factory(App\Question::class)->create([
            'text'      => 'Ingresa un código promocional o código de referencia',
            'type'      => 'SIMPLE_TEXT',
            'step_uuid' => DB::table('steps')->whereOrder(2)->first()->uuid,
            'order'     => 4,
            'required'  => false,
        ]);

        factory(App\Question::class)->create([
            'text'      => 'Confirmo que todas las respuestas que he seleccionado son verdaderas entiendo que de ser deshonestas podria no aplicar para un préstamo de Diimo',
            'type'      => 'CHECK',
            'step_uuid' => DB::table('steps')->whereOrder(2)->first()->uuid,
            'order'     => 5,
        ]);

        // Step 3
        factory(App\Question::class)->create([
            'text'      => '¿Cuál es su nivel más alto de estudio?',
            'step_uuid' => DB::table('steps')->whereOrder(3)->first()->uuid,
            'order'     => 1,
        ])->options()->saveMany(
            [
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Ninguno']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Primaria']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Bachillerato']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Universitario']),
            ]
        );

        factory(App\Question::class)->create([
            'text'      => '¿Seleccione todo lo que aplique contigo?',
            'step_uuid' => DB::table('steps')->whereOrder(3)->first()->uuid,
            'type'      => 'SINGLE',
            'order'     => 2,
        ])->options()->saveMany(
            [
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Tengo trabajo (Me pagan un salario)']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Soy autoempleado (Trabajo por mi cuenta)']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Soy estudiante']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'No tengo ingresos']),
            ]
        );

        // Step 4
        factory(App\Question::class)->create([
            'text'      => '¿Para que usaras el préstamo?',
            'step_uuid' => DB::table('steps')->whereOrder(3)->first()->uuid,
            'order'     => 1,
        ])->options()->saveMany(
            [
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Para un negocio']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Uso personal']),
            ]
        );

        factory(App\Question::class)->create([
            'text'      => 'Por favor detalla con exactitud en que usaras más tu préstamo',
            'step_uuid' => DB::table('steps')->whereOrder(3)->first()->uuid,
            'type'      => 'LARGE_TEXT',
            'order'     => 2,
        ]);

        factory(App\Question::class)->create([
            'text'      => '¿Tiene prestamo vigente con alguna otra institución?',
            'step_uuid' => DB::table('steps')->whereOrder(3)->first()->uuid,
            'order'     => 3,
        ])->options()->saveMany(
            [
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Si']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'No']),
            ]
        );

        // Step 5
        factory(App\Question::class)->create([
            'text'      => '¿De cuanto son estos ingresos?',
            'step_uuid' => DB::table('steps')->whereOrder(3)->first()->uuid,
            'type'      => 'SINGLE',
            'order'     => 1,
        ])->options()->saveMany(
            [
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Menos de $250 al mes']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Entre $251 a $500 al mes']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Más de $501 al mes']),
            ]
        );

        factory(App\Question::class)->create([
            'text'      => '¿Desde cuando tiene esta fuente de ingresos?',
            'step_uuid' => DB::table('steps')->whereOrder(3)->first()->uuid,
            'order'     => 2,
        ])->options()->saveMany(
            [
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Menos de 6 meses']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => '6-24 meses']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => '+2 años']),
            ]
        );

        factory(App\Question::class)->create([
            'text'      => '¿Tiene alguna otra fuente de ingresos?',
            'step_uuid' => DB::table('steps')->whereOrder(3)->first()->uuid,
            'order'     => 3,
        ])->options()->saveMany(
            [
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'Si']),
                new Option(['uuid' => Uuid::uuid4(), 'text' => 'No']),
            ]
        );
    }
}
