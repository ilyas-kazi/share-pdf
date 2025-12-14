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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('original_name');
            $table->string('path');
            $table->string('preview_image')->nullable();
            $table->integer('download_count')->default(0);
            $table->timestamps();
            $table->userstamps();               // adds created_by, updated_by (nullable)
            $table->userstampSoftDeletes();     // optional, for deleted_by (requires soft deletes)
            $table->softDeletes();              // adds deleted_at, required for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
