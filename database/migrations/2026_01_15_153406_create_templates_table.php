<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('site_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['hero', 'content', 'features', 'contact', 'testimonial', 'full_page', 'layout']);
            $table->string('thumbnail')->nullable();
            $table->json('content');
            $table->json('preview_data')->nullable();
            $table->string('category')->default('general');
            $table->enum('visibility', ['private', 'public', 'system'])->default('private');
            $table->integer('usage_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};