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
        Schema::create('hr_leaves', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name');
            $table->string('leave_type');
            $table->integer('duration');
            $table->string('status')->default('Menunggu'); // 'Menunggu', 'Disetujui', 'Ditolak'
            $table->timestamps();
        });

        Schema::create('hr_documents', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('file_path');
            $table->string('file_size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_leaves');
        Schema::dropIfExists('hr_documents');
    }
};
