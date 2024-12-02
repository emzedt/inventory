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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("address");
            $table->string("contact", 30);
            $table->char("uuid", 36)->unique()->index();
            $table->string("slug")->unique()->index();
            $table->boolean("published")->default(true)->index();
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
        Schema::dropIfExists('suppliers');
    }
};
