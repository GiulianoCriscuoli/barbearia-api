<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('avatar')->default('default.png');
            $table->string('email')->unique();
            $table->string('password');
        });

        Schema::create('favoritos', function (Blueprint $table) {
            $table->id();
            $table->integer('usuario_id');
            $table->integer('barbeiro_id');
        });

        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->integer('usuario_id');
            $table->integer('barbeiro_id');
            $table->datetime('data_agendamento');
        });


        Schema::create('barbeiros', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('avatar');
            $table->float('estrelas')->default(0);
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
        });

        Schema::create('barbeiro_fotos', function (Blueprint $table) {
            $table->id();
            $table->integer('barbeiro_id');
            $table->string('url');
        });

        Schema::create('barbeiro_avaliacoes', function (Blueprint $table) {
            $table->id();
            $table->integer('barbeiro_id');
            $table->string('avaliacao');
        });

        Schema::create('barbeiro_servicos', function (Blueprint $table) {
            $table->id();
            $table->integer('barbeiro_id');
            $table->string('nome');
            $table->float('preco');
        });

        Schema::create('barbeiro_depoimentos', function (Blueprint $table) {
            $table->id();
            $table->integer('barbeiro_id');
            $table->string('nome');
            $table->float('avaliacao');
            $table->string('depoimento');
        });

        Schema::create('barbeiro_diponibilidades', function (Blueprint $table) {
            $table->id();
            $table->integer('barbeiro_id');
            $table->integer('weekday');
            $table->text('hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('favoritos');
        Schema::dropIfExists('agendamentos');
        Schema::dropIfExists('barbeiros');
        Schema::dropIfExists('barbeiro_fotos');
        Schema::dropIfExists('barbeiro_avaliacoes');
        Schema::dropIfExists('barbeiro_servicos');
        Schema::dropIfExists('barbeiro_depoimentos');
        Schema::dropIfExists('barbeiro_diponibilidades');
    }
};
