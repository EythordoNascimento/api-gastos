<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDespesasPeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('despesas_pe', function (Blueprint $table) {
            $table->id('id_despesa');
            $table->text('finalidade'); // finalidade_ordem_bancaria
            $table->string('credor', 255); // masc_nm_credor_ob
            $table->string('situacao', 50); // situacao
            $table->string('ordem_bancaria', 50); // num_ordem_bancaria
            $table->string('unidade_gestora', 150); // unidade_gestora
            $table->date('data'); // dt_lancamento
            $table->decimal('valor', 14, 2); // vlr_ordem_bancaria
            $table->string('numero_empenho', 50); // num_emp
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('despesas_pe');
    }
}
