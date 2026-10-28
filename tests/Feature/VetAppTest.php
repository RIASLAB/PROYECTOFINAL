<?php

namespace Tests\Feature;

use App\Models\Cita;
use App\Models\Factura;
use App\Models\Historia;
use App\Models\Mascota;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas funcionales del aplicativo Lugo Vet.
 * Los métodos cp01 a cp10 corresponden a los casos de prueba del Apéndice D del trabajo de grado.
 */
class VetAppTest extends TestCase
{
    use RefreshDatabase;

    private function usuario(string $role = 'user', array $extra = []): User
    {
        return User::factory()->create(array_merge(['role' => $role, 'status' => 'activo'], $extra));
    }

    private function mascotaDe(User $dueno, array $extra = []): Mascota
    {
        return Mascota::create(array_merge(['nombre' => 'Firulais', 'especie' => 'perro', 'dueno' => (string) $dueno->id], $extra));
    }

    private function cita(Mascota $m, array $extra = []): Cita
    {
        return Cita::create(array_merge([
            'mascota_id' => $m->id, 'servicio' => 'consulta', 'fecha' => now()->addDay()->toDateString(),
            'hora' => '10:00', 'motivo' => 'Control', 'estado' => 'pendiente',
        ], $extra));
    }

    private function datosCita(Mascota $m, array $extra = []): array
    {
        return array_merge([
            'mascota_id' => $m->id, 'servicio' => 'consulta', 'fecha' => now()->addDays(2)->toDateString(),
            'hora' => '09:30', 'motivo' => 'Vacunas',
        ], $extra);
    }

    // ---------------------------------------------------------------- CP01 · HU01
    public function test_cp01_registro_con_correo_existente_es_rechazado(): void
    {
        $this->usuario('user', ['email' => 'ana@correo.com']);

        $this->post('/register', [
            'name' => 'Ana', 'email' => 'ana@correo.com', 'password' => 'password',
            'password_confirmation' => 'password', 'acepta_datos' => '1',
        ])->assertSessionHasErrors('email');

        $this->assertSame(1, User::where('email', 'ana@correo.com')->count());
    }

    public function test_registro_exige_autorizacion_de_datos(): void
    {
        $this->post('/register', [
            'name' => 'Luis', 'email' => 'luis@correo.com', 'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('acepta_datos');

        $this->assertGuest();
    }

    public function test_registro_valido_crea_cliente(): void
    {
        $this->post('/register', [
            'name' => 'Luis', 'email' => 'luis@correo.com', 'password' => 'password',
            'password_confirmation' => 'password', 'acepta_datos' => '1',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertSame('user', User::where('email', 'luis@correo.com')->value('role'));
    }

    // ---------------------------------------------------------------- CP02 · HU02
    public function test_cp02_mascota_sin_especie_es_rechazada(): void
    {
        $cliente = $this->usuario();

        $this->actingAs($cliente)->post('/mascotas', ['nombre' => 'Michi'])
            ->assertSessionHasErrors('especie');

        $this->assertSame(0, Mascota::count());
    }

    public function test_cliente_solo_ve_sus_mascotas(): void
    {
        $ana = $this->usuario();
        $otro = $this->usuario();
        $this->mascotaDe($ana, ['nombre' => 'MascotaDeAna']);
        $this->mascotaDe($otro, ['nombre' => 'MascotaAjena']);

        $this->actingAs($ana)->get('/mascotas')
            ->assertOk()->assertSee('MascotaDeAna')->assertDontSee('MascotaAjena');
    }

    // ---------------------------------------------------------------- CP03 · HU03
    public function test_cp03_no_permite_dos_citas_de_la_misma_mascota_a_la_misma_hora(): void
    {
        $cliente = $this->usuario();
        $m = $this->mascotaDe($cliente);

        $this->actingAs($cliente)->post('/citas', $this->datosCita($m))->assertSessionHasNoErrors();
        $this->actingAs($cliente)->post('/citas', $this->datosCita($m))->assertSessionHasErrors('hora');

        $this->assertSame(1, Cita::count());
        $this->assertSame('pendiente', Cita::first()->estado);
    }

    public function test_cita_registra_tipo_de_servicio_procedimiento(): void
    {
        $cliente = $this->usuario();
        $m = $this->mascotaDe($cliente);

        $this->actingAs($cliente)->post('/citas', $this->datosCita($m, ['servicio' => 'ecografia']))->assertSessionHasNoErrors();

        $this->assertTrue(Cita::first()->es_procedimiento);
    }

    public function test_cliente_no_puede_agendar_con_mascota_ajena(): void
    {
        $ajena = $this->mascotaDe($this->usuario());

        $this->actingAs($this->usuario())->post('/citas', $this->datosCita($ajena))
            ->assertSessionHasErrors('mascota_id');
    }

    public function test_cliente_solo_ve_sus_citas(): void
    {
        $ana = $this->usuario();
        $this->cita($this->mascotaDe($ana), ['motivo' => 'CitaDeAna']);
        $this->cita($this->mascotaDe($this->usuario()), ['motivo' => 'CitaAjena']);

        $this->actingAs($ana)->get('/citas')
            ->assertOk()->assertSee('CitaDeAna')->assertDontSee('CitaAjena');
    }

    // ---------------------------------------------------------------- CP04 · HU04
    public function test_cp04_cliente_no_puede_editar_cita_ajena(): void
    {
        $cita = $this->cita($this->mascotaDe($this->usuario()));

        $this->actingAs($this->usuario())->get("/citas/{$cita->id}/edit")->assertForbidden();
    }

    public function test_cliente_no_puede_marcar_su_cita_como_completada(): void
    {
        $cliente = $this->usuario();
        $m = $this->mascotaDe($cliente);
        $cita = $this->cita($m);

        $this->actingAs($cliente)->put("/citas/{$cita->id}", $this->datosCita($m, ['estado' => 'completada']));

        $this->assertSame('pendiente', $cita->fresh()->estado);
    }

    // ---------------------------------------------------------------- CP05 · HU08
    public function test_cp05_no_asigna_veterinario_con_horario_ocupado(): void
    {
        $recep = $this->usuario('recepcionista');
        $vet = $this->usuario('veterinario');
        $m = $this->mascotaDe($this->usuario());
        $this->cita($m, ['vet_id' => $vet->id]);
        $otra = $this->cita($this->mascotaDe($this->usuario()));

        $this->actingAs($recep)->patch("/agenda/{$otra->id}/asignar-vet", ['vet_id' => $vet->id])
            ->assertSessionHasErrors('vet_id');

        $this->assertNull($otra->fresh()->vet_id);
    }

    public function test_recepcion_confirma_y_cancela_citas(): void
    {
        $recep = $this->usuario('recepcionista');
        $c1 = $this->cita($this->mascotaDe($this->usuario()));
        $c2 = $this->cita($this->mascotaDe($this->usuario()), ['hora' => '11:00']);

        $this->actingAs($recep)->patch("/agenda/{$c1->id}/confirmar")->assertSessionHasNoErrors();
        $this->actingAs($recep)->patch("/agenda/{$c2->id}/cancelar")->assertSessionHasNoErrors();

        $this->assertSame('confirmada', $c1->fresh()->estado);
        $this->assertSame('cancelada', $c2->fresh()->estado);
    }

    // ---------------------------------------------------------------- CP06 · HU09
    public function test_cp06_agenda_conserva_filtros_al_paginar(): void
    {
        $recep = $this->usuario('recepcionista');
        $vet = $this->usuario('veterinario');
        $m = $this->mascotaDe($this->usuario());
        foreach (range(1, 14) as $i) {
            $this->cita($m, ['hora' => sprintf('%02d:00', 6 + $i), 'vet_id' => $vet->id]);
        }

        $this->actingAs($recep)->get("/agenda?vet_id={$vet->id}")
            ->assertOk()
            ->assertSee('vet_id='.$vet->id.'&amp;page=2', false);
    }

    // ---------------------------------------------------------------- CP07 · HU14
    public function test_cp07_veterinario_no_asignado_no_registra_historia(): void
    {
        $vetAsignado = $this->usuario('veterinario');
        $otroVet = $this->usuario('veterinario');
        $cita = $this->cita($this->mascotaDe($this->usuario()), ['vet_id' => $vetAsignado->id]);

        $this->actingAs($otroVet)->post('/historias', ['cita_id' => $cita->id, 'motivo' => 'Consulta'])
            ->assertForbidden();

        $this->actingAs($vetAsignado)->post('/historias', ['cita_id' => $cita->id, 'motivo' => 'Consulta'])
            ->assertSessionHasNoErrors();

        $this->assertSame(1, Historia::count());
    }

    // ---------------------------------------------------------------- HU05 y HU16
    public function test_cliente_ve_las_historias_de_sus_mascotas(): void
    {
        $ana = $this->usuario();
        $vet = $this->usuario('veterinario');
        $cita = $this->cita($this->mascotaDe($ana), ['vet_id' => $vet->id]);
        $h = Historia::create(['cita_id' => $cita->id, 'vet_id' => $vet->id, 'motivo' => 'OtitisDeFirulais']);

        $this->actingAs($ana)->get('/client/historias')->assertOk()->assertSee('OtitisDeFirulais');
        $this->actingAs($ana)->get("/historias/{$h->id}")->assertOk();
        $this->actingAs($this->usuario())->get("/historias/{$h->id}")->assertForbidden();
    }

    public function test_veterinario_consulta_historial_completo_de_la_mascota(): void
    {
        $vet1 = $this->usuario('veterinario');
        $vet2 = $this->usuario('veterinario');
        $m = $this->mascotaDe($this->usuario());
        $c1 = $this->cita($m, ['vet_id' => $vet1->id]);
        Historia::create(['cita_id' => $c1->id, 'vet_id' => $vet1->id, 'motivo' => 'HistoriaDelVetUno']);

        $this->actingAs($vet2)->get("/vet/mascotas/{$m->id}/historias")
            ->assertOk()->assertSee('HistoriaDelVetUno');
    }

    public function test_historia_se_descarga_en_pdf(): void
    {
        $ana = $this->usuario();
        $vet = $this->usuario('veterinario');
        $cita = $this->cita($this->mascotaDe($ana), ['vet_id' => $vet->id]);
        $h = Historia::create(['cita_id' => $cita->id, 'vet_id' => $vet->id, 'motivo' => 'Control']);

        $this->actingAs($ana)->get("/historias/{$h->id}/pdf")
            ->assertOk()->assertHeader('content-type', 'application/pdf');
    }

    // ---------------------------------------------------------------- CP08 · HU11
    public function test_cp08_no_genera_segunda_factura_para_historia_pagada(): void
    {
        $recep = $this->usuario('recepcionista');
        $vet = $this->usuario('veterinario');
        $cita = $this->cita($this->mascotaDe($this->usuario()), ['vet_id' => $vet->id]);
        $h = Historia::create(['cita_id' => $cita->id, 'vet_id' => $vet->id, 'motivo' => 'Control']);

        $this->actingAs($vet)->post("/historias/{$h->id}/enviar-caja")->assertSessionHasNoErrors();
        $this->assertNotNull($h->fresh()->enviado_caja_at);

        $datos = ['historia_id' => $h->id, 'impuesto' => 19,
                  'items' => [['descripcion' => 'Consulta', 'cantidad' => 1, 'precio' => 50000]]];
        $this->actingAs($recep)->post('/facturas', $datos);
        $factura = Factura::first();
        $this->assertEquals(59500, (float) $factura->total);

        $this->actingAs($recep)->patch("/facturas/{$factura->id}/pagar");
        $this->assertSame('pagada', $factura->fresh()->estado);

        $this->actingAs($recep)->post('/facturas', $datos)->assertSessionHas('error');
        $this->assertSame(1, Factura::count());
    }

    public function test_factura_pendiente_se_puede_anular(): void
    {
        $recep = $this->usuario('recepcionista');
        $vet = $this->usuario('veterinario');
        $cita = $this->cita($this->mascotaDe($this->usuario()), ['vet_id' => $vet->id]);
        $h = Historia::create(['cita_id' => $cita->id, 'vet_id' => $vet->id, 'motivo' => 'Control']);
        $f = Factura::create(['historia_id' => $h->id, 'subtotal' => 1000, 'impuesto' => 0, 'total' => 1000, 'estado' => 'pendiente']);

        $this->actingAs($recep)->patch("/facturas/{$f->id}/anular");

        $this->assertSame('anulada', $f->fresh()->estado);
    }

    public function test_cliente_no_accede_a_facturacion(): void
    {
        // El middleware de roles devuelve al cliente a su propio panel
        $this->actingAs($this->usuario())->get('/facturas')->assertRedirect(route('client.dashboard'));
        $this->actingAs($this->usuario())->get('/caja')->assertRedirect(route('client.dashboard'));
    }

    // ---------------------------------------------------------------- CP09 · HU17
    public function test_usuario_desactivado_con_sesion_abierta_pierde_el_acceso(): void
    {
        $u = $this->usuario('veterinario');
        $this->actingAs($u);
        $u->update(['status' => 'inactivo']);

        $this->get('/vet/dashboard')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_cp09_usuario_inactivo_no_puede_iniciar_sesion(): void
    {
        $u = $this->usuario('user', ['status' => 'inactivo']);

        $this->post('/login', ['email' => $u->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    // ---------------------------------------------------------------- CP10 · HU18
    public function test_cp10_cliente_no_accede_a_gestion_de_usuarios(): void
    {
        $cliente = $this->usuario();

        // Se niega el acceso y se redirige al cliente a su panel con un mensaje
        $this->actingAs($cliente)->get('/admin/users')
            ->assertRedirect(route('client.dashboard'))->assertSessionHas('error');
        $this->actingAs($cliente)->post('/admin/users', [
            'name' => 'Intruso', 'email' => 'x@x.com', 'password' => 'password123', 'role' => 'admin', 'status' => 'activo',
        ])->assertRedirect(route('client.dashboard'));

        $this->assertFalse(User::where('email', 'x@x.com')->exists());
    }

    public function test_cada_rol_solo_entra_a_su_tablero(): void
    {
        $this->actingAs($this->usuario('veterinario'))->get('/admin/dashboard')->assertRedirect(route('vet.dashboard'));
        $this->actingAs($this->usuario('recepcionista'))->get('/vet/dashboard')->assertRedirect(route('reception.dashboard'));
        $this->actingAs($this->usuario('admin'))->get('/admin/dashboard')->assertOk();
        $this->actingAs($this->usuario('veterinario'))->get('/vet/dashboard')->assertOk();
        $this->actingAs($this->usuario('recepcionista'))->get('/reception/dashboard')->assertOk();
        $this->actingAs($this->usuario())->get('/client/dashboard')->assertOk();
    }

    // ---------------------------------------------------------------- HU07
    public function test_recepcion_registra_mascota_a_nombre_de_un_cliente(): void
    {
        $recep = $this->usuario('recepcionista');
        $cliente = $this->usuario();

        $this->actingAs($recep)->post('/mascotas', ['nombre' => 'Luna', 'especie' => 'gato', 'dueno_id' => $cliente->id])
            ->assertSessionHasNoErrors();
        $this->assertTrue(Mascota::first()->perteneceA($cliente));

        // Un id que no es de un cliente registrado se rechaza
        $this->actingAs($recep)->post('/mascotas', ['nombre' => 'Sol', 'especie' => 'gato', 'dueno_id' => 9999])
            ->assertSessionHasErrors('dueno_id');
    }

    // ---------------------------------------------------------------- Todas las pantallas cargan con cada rol
    public function test_pantallas_principales_cargan_sin_errores(): void
    {
        $admin = $this->usuario('admin');
        $recep = $this->usuario('recepcionista');
        $vet = $this->usuario('veterinario');
        $cliente = $this->usuario();
        $m = $this->mascotaDe($cliente);
        $cita = $this->cita($m, ['vet_id' => $vet->id, 'servicio' => 'cirugia']);
        $h = Historia::create(['cita_id' => $cita->id, 'vet_id' => $vet->id, 'motivo' => 'Control', 'pendiente_cobro' => true]);

        $rutas = [
            [$cliente, ['/client/dashboard', '/mascotas', '/mascotas/create', '/citas', '/citas/create', "/citas/{$cita->id}/edit", '/client/historias', "/historias/{$h->id}", '/profile']],
            [$recep, ['/reception/dashboard', '/agenda', '/mascotas', '/mascotas/create', '/caja', '/caja/pendientes', '/reportes/caja', '/facturas', "/facturas/create-from-historia/{$h->id}"]],
            [$vet, ['/vet/dashboard', '/agenda', '/vet/historias', '/vet/recetas', "/vet/historias/{$h->id}/recetas", "/vet/historias/{$h->id}/recetas/create", "/vet/mascotas/{$m->id}/historias"]],
            [$admin, ['/admin/dashboard', '/admin/users', '/agenda', '/mascotas', '/facturas']],
        ];

        foreach ($rutas as [$user, $urls]) {
            foreach ($urls as $url) {
                $res = $this->actingAs($user)->get($url);
                $this->assertContains($res->getStatusCode(), [200, 302], "Falla {$url} con rol {$user->role}: ".$res->getStatusCode());
            }
        }
    }

    // ---------------------------------------------------------------- HU15
    public function test_receta_registra_medicamentos_con_dosis(): void
    {
        $vet = $this->usuario('veterinario');
        $cita = $this->cita($this->mascotaDe($this->usuario()), ['vet_id' => $vet->id]);
        $h = Historia::create(['cita_id' => $cita->id, 'vet_id' => $vet->id, 'motivo' => 'Otitis']);

        $this->actingAs($vet)->post("/vet/historias/{$h->id}/recetas", [
            'indicaciones' => 'Limpiar el oído dos veces al día',
            'items' => [['medicamento' => 'Otomax', 'dosis' => '5 gotas', 'frecuencia' => 'cada 12 h', 'duracion' => '7 días', 'via' => 'ótica']],
        ])->assertSessionHasNoErrors();

        $receta = \App\Models\Receta::first();
        $this->assertSame('Otomax', $receta->items->first()->medicamento);
        $this->assertSame('5 gotas', $receta->items->first()->dosis);

        // Sin medicamentos no se guarda
        $this->actingAs($vet)->post("/vet/historias/{$h->id}/recetas", [
            'indicaciones' => 'Reposo', 'items' => [['medicamento' => '']],
        ])->assertSessionHasErrors('items');
        $this->assertSame(1, \App\Models\Receta::count());

        $this->actingAs($vet)->get("/vet/recetas/{$receta->id}")->assertOk()->assertSee('Otomax');
    }

    // ---------------------------------------------------------------- Evaluación heurística (correcciones)
    public function test_mensajes_de_validacion_en_espanol(): void
    {
        $cliente = $this->usuario();
        $this->actingAs($cliente)->post('/mascotas', ['nombre' => 'Luna'])
            ->assertSessionHasErrors(['especie' => 'Debes indicar la especie.']);
    }

    public function test_confirmacion_visible_despues_de_guardar(): void
    {
        $cliente = $this->usuario();
        $this->actingAs($cliente)->followingRedirects()
            ->post('/mascotas', ['nombre' => 'Luna', 'especie' => 'Perro'])
            ->assertOk()
            ->assertSee('id="lv-toasts"', false);
    }

    public function test_menu_superior_en_espanol(): void
    {
        $cliente = $this->usuario();
        $this->actingAs($cliente)->get('/mascotas')->assertOk()->assertSee('Inicio')->assertDontSee('>Dashboard<', false);
    }

    public function test_indicadores_de_perros_y_gatos_cuentan_sin_importar_mayusculas(): void
    {
        $cliente = $this->usuario();
        Mascota::create(['nombre' => 'Luna', 'especie' => 'Perro', 'dueno' => $cliente->id]);
        Mascota::create(['nombre' => 'Rocky', 'especie' => 'perro', 'dueno' => $cliente->id]);
        Mascota::create(['nombre' => 'Michi', 'especie' => 'Gato', 'dueno' => $cliente->id]);
        $r = $this->actingAs($cliente)->get('/mascotas')->assertOk();
        $this->assertSame(2, $r->viewData('perros'));
        $this->assertSame(1, $r->viewData('gatos'));
    }

    // ---------------------------------------------------------------- Prueba de usabilidad (correcciones)
    public function test_factura_nueva_trae_el_nombre_del_cliente(): void
    {
        $recep = $this->usuario('recepcionista');
        $vet = $this->usuario('veterinario');
        $cliente = $this->usuario('user', ['name' => 'María López']);
        $cita = $this->cita($this->mascotaDe($cliente), ['vet_id' => $vet->id]);
        $h = Historia::create(['cita_id' => $cita->id, 'vet_id' => $vet->id, 'motivo' => 'Control']);
        \App\Models\Receta::create(['historia_id' => $h->id, 'vet_id' => $vet->id, 'mascota_id' => $cita->mascota_id, 'fecha' => now(), 'indicaciones' => 'Reposo']);
        $this->actingAs($recep)->get("/facturas/create-from-historia/{$h->id}")
            ->assertOk()->assertSee('value="María López"', false);
    }

    public function test_veterinario_ve_enviar_a_caja_en_sus_historias(): void
    {
        $vet = $this->usuario('veterinario');
        $cita = $this->cita($this->mascotaDe($this->usuario()), ['vet_id' => $vet->id]);
        Historia::create(['cita_id' => $cita->id, 'vet_id' => $vet->id, 'motivo' => 'Control']);
        $this->actingAs($vet)->get('/vet/historias')->assertOk()->assertSee('Enviar a caja');
    }

    public function test_hora_obligatoria_con_mensaje_en_espanol(): void
    {
        $cliente = $this->usuario();
        $m = $this->mascotaDe($cliente);
        $this->actingAs($cliente)->get('/citas/create')->assertOk()->assertSee('name="hora" type="time"', false);
        $this->actingAs($cliente)->post('/citas', $this->datosCita($m, ['hora' => '']))
            ->assertSessionHasErrors(['hora' => 'Debes indicar la hora de la cita.']);
    }

    public function test_veterinario_ve_en_la_agenda_las_acciones_de_su_cita(): void
    {
        $vet = $this->usuario('veterinario');
        $this->cita($this->mascotaDe($this->usuario()), ['vet_id' => $vet->id, 'estado' => 'confirmada', 'fecha' => now()->toDateString()]);
        $this->actingAs($vet)->get('/agenda')->assertOk()
            ->assertSee('Marcar atendida')->assertSee('open-historia', false)->assertDontSee('ag-hint', false);
    }

    public function test_el_seeder_crea_el_administrador_inicial(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
        $email = env('ADMIN_EMAIL', 'admin@lugovet.com');
        $admin = User::where('email', $email)->first();
        $this->assertNotNull($admin);
        $this->assertSame('admin', $admin->role);
        $this->post('/login', ['email' => $email, 'password' => env('ADMIN_PASSWORD', 'LugoVet2026*')])->assertRedirect();
        $this->assertAuthenticatedAs($admin);
    }
}
