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
        Schema::create('comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->uuidMorphs('commentable');
            $table->longText('content');
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['commentable_id', 'commentable_type']);
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->foreignUuid('parent_id')->nullable()->constrained('comments')->cascadeOnDelete();
            
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
