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
        Schema::create('incomings', function (Blueprint $table) {
            $table->id();
            $table->string("image")->nullable();
            $table->string("reference")->nullable();
            $table->integer("quantity")->default(0);
            $table->dateTime("date_received");
            $table->char("uuid", 36)->unique()->index();
            $table->string("slug")->unique()->index();
            $table->foreignId('product_id')->constrained(
                table: 'products',
                indexName: 'products_product_id'
            );
            $table->foreignId('supplier_id')->constrained(
                table: 'suppliers',
                indexName: 'suppliers_supplier_id'
            );
            $table->foreignId('user_id')->constrained(
                table: 'users',
                indexName: 'users_user_id'
            );
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomings');
    }
};
