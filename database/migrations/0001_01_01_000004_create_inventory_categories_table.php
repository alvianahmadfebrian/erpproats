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
        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Insert default categories to match original setup
        DB::table('inventory_categories')->insert([
            ['name' => 'Pianos', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Keyboards', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Guitars', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Strings', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Percussion', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_categories');
    }
};
