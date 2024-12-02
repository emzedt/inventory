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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id()->index();
            $table->integer("stock_in")->default(0);
            $table->integer("stock_out")->default(0);
            $table->string("location")->index();
            $table->foreignId('product_id')->constrained(
                table: 'products',
                indexName: 'products_product_id'
            );
            $table->foreignId('user_id')->constrained(
                table: 'users',
                indexName: 'users_user_id'
            );
            $table->char("uuid", 36)->unique()->index();
            $table->string("slug")->unique()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
