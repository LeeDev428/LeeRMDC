<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcedurePricesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('procedure_prices', function (Blueprint $table) {
            $table->id();
            $table->string('procedure_name');
            $table->decimal('price', 8, 2); // Price field (up to 999,999.99)
            $table->string('duration')->nullable(); // Duration field (e.g., '30 minutes')
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('procedure_prices');
    }
}
