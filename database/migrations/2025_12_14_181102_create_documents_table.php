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
            $table->string('title');
            $table->string('description', 1000)->nullable();
            $table->string('original_name');
            $table->string('path');
            $table->string('preview_image')->nullable();

            $table->json('metadata')->nullable();
                // $table->string('author')->nullable();
                // $table->integer('total_pages')->default(0);
                // $table->integer('size_in_kb')->default(0);

            $table->integer('download_count')->default(0);
            $table->dateTime('last_downloaded_at')->nullable();

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
