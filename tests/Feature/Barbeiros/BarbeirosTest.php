<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Barbeiro;


class BarbeirosTest extends TestCase {

    public function test_barbeiros_lista () {

        $barbeiros = Barbeiro::all();

        $response = $this->getJson('/api/barbeiros');

       $response
        ->assertStatus(200)
        ->assertJsonCount($barbeiros->count(), 'mensagem')
        ->assertJson([
            'mensagem' => $barbeiros->toArray()
        ]);
    }
}
