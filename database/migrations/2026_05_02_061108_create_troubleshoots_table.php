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
        Schema::create('troubleshoots', function (Blueprint $table) {
            $table->id();
            $table->string('ticket')->unique(); // Wajib untuk pelacakan
            $table->text('name'); // Tindakan yang sudah dilakukan oleh NOC

            $table->string('client'); // Wajib untuk pelacakan
            $table->text('complaint'); // Tindakan yang sudah dilakukan oleh NOC

            // Waktu 
            $table->dateTime('incident_time')->nullable();
            $table->dateTime('response_time')->nullable();
            $table->dateTime('completion_time')->nullable();

            // Penanganan / Tindakan
            $table->text('action'); // Tindakan yang sudah dilakukan oleh NOC
            $table->text('root_cause')->nullable(); // Penyebab utama (nullable jika belum diketahui)

            $table->string('handled_by')->nullable();

            // Status dan Prioritas
            $table->string('priority')->default('medium');
            // $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->string('status')->default('closed');
            // $table->enum('status', ['open', 'progress', 'closed'])->default('closed');
            $table->string('type')->default('system');
            // $table->enum('type', ['system', 'hardware', 'network'])->default('system');

            $table->text('notes')->nullable();

            $table->json('images')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('troubleshoots');
    }
};
