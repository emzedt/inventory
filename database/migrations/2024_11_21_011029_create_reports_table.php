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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string("period")->index();
            $table->integer("starting_stock")->default(0);
            $table->integer("stock_in")->default(0);
            $table->integer("stock_out")->default(0);
            $table->foreignId('user_id')->constrained(
                table: 'users',
                indexName: 'users_user_id'
            );
            $table->char("uuid", 36)->unique()->index();
            $table->string('slug')->unique()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
