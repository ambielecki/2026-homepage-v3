<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepages', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(false)->index();
            $table->foreignId('hero_image_id')->nullable()->constrained('images')->nullOnDelete();
            $table->string('hero_headline');
            $table->string('hero_title');
            $table->text('hero_description');
            $table->string('expertise_headline');
            $table->string('expertise_title');
            $table->string('projects_headline');
            $table->string('projects_title');
            $table->text('projects_description');
            $table->string('experience_headline');
            $table->string('experience_title');
            $table->text('experience_description');
            $table->string('contact_headline');
            $table->string('contact_title');
            $table->text('contact_description');
            $table->string('github_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->timestamps();
        });

        Schema::create('homepage_expertise_cards', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('homepage_id')->constrained('homepages')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('homepage_projects', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('homepage_id')->constrained('homepages')->cascadeOnDelete();
            $table->foreignId('image_id')->nullable()->constrained('images')->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('homepage_experiences', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('homepage_id')->constrained('homepages')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_experiences');
        Schema::dropIfExists('homepage_projects');
        Schema::dropIfExists('homepage_expertise_cards');
        Schema::dropIfExists('homepages');
    }
};
