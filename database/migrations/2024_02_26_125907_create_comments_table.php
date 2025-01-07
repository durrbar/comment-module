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
            $table->uuid('id')->primary(); // Primary key as UUID
            $table->foreignUuid('parent_id')->nullable()->constrained('comments')->cascadeOnDelete(); // For nested comments
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete(); // Foreign key for user
            $table->uuidMorphs('commentable'); // Polymorphic relation columns
            $table->longText('content'); // Content of the comment
            $table->timestamps(); // Created and updated timestamps
            $table->softDeletes(); // Soft delete support

            // Indexes for performance
            $table->index(['commentable_id', 'commentable_type']); // Index for polymorphic relations
            $table->index('parent_id'); // Index for parent comments
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
