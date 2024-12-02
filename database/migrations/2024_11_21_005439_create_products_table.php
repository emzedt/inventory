<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("image")->nullable();
            $table->string("category");
            $table->string("unit", 10);
            $table->string("name");
            $table->double("selling_price")->default(0);
            $table->double("purchase_price")->default(0);

            // Definisi foreign key untuk user_id
            $table->foreignId('user_id')->constrained('users')->onDelete('set null');

            // Definisi kolom threshold_id sebelum foreign key
            $table->foreignId('threshold_id')->nullable()->constrained('thresholds')->nullOnDelete();

            $table->char("uuid", 36)->unique()->index();
            $table->string('slug')->unique()->index();
            $table->boolean("published")->default(true)->index(); // Untuk status produk
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
