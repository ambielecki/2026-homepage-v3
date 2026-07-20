<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_project_assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('homepage_id');
            $table->foreignId('homepage_project_id');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('homepage_id', 'hp_project_assign_homepage_fk')->references('id')->on('homepages')->cascadeOnDelete();
            $table->foreign('homepage_project_id', 'hp_project_assign_project_fk')->references('id')->on('homepage_projects')->cascadeOnDelete();
            $table->unique(['homepage_id', 'homepage_project_id'], 'homepage_project_assignments_unique');
        });

        Schema::create('homepage_experience_assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('homepage_id');
            $table->foreignId('homepage_experience_id');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('homepage_id', 'hp_experience_assign_homepage_fk')->references('id')->on('homepages')->cascadeOnDelete();
            $table->foreign('homepage_experience_id', 'hp_experience_assign_experience_fk')->references('id')->on('homepage_experiences')->cascadeOnDelete();
            $table->unique(['homepage_id', 'homepage_experience_id'], 'homepage_experience_assignments_unique');
        });

        Schema::create('homepage_expertise_card_assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('homepage_id');
            $table->foreignId('homepage_expertise_card_id');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('homepage_id', 'hp_expertise_assign_homepage_fk')->references('id')->on('homepages')->cascadeOnDelete();
            $table->foreign('homepage_expertise_card_id', 'hp_expertise_assign_card_fk')->references('id')->on('homepage_expertise_cards')->cascadeOnDelete();
            $table->unique(['homepage_id', 'homepage_expertise_card_id'], 'homepage_expertise_assignments_unique');
        });

        $now = now();

        DB::table('homepage_projects')
            ->whereNotNull('homepage_id')
            ->orderBy('id')
            ->each(function (object $project) use ($now): void {
                DB::table('homepage_project_assignments')->insert([
                    'homepage_id' => $project->homepage_id,
                    'homepage_project_id' => $project->id,
                    'sort_order' => $project->sort_order,
                    'is_active' => $project->is_active,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            });

        DB::table('homepage_experiences')
            ->whereNotNull('homepage_id')
            ->orderBy('id')
            ->each(function (object $experience) use ($now): void {
                DB::table('homepage_experience_assignments')->insert([
                    'homepage_id' => $experience->homepage_id,
                    'homepage_experience_id' => $experience->id,
                    'sort_order' => $experience->sort_order,
                    'is_active' => $experience->is_active,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            });

        DB::table('homepage_expertise_cards')
            ->whereNotNull('homepage_id')
            ->orderBy('id')
            ->each(function (object $card) use ($now): void {
                DB::table('homepage_expertise_card_assignments')->insert([
                    'homepage_id' => $card->homepage_id,
                    'homepage_expertise_card_id' => $card->id,
                    'sort_order' => $card->sort_order,
                    'is_active' => $card->is_active,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            });

        Schema::table('homepage_projects', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('homepage_id');
            $table->dropColumn(['sort_order', 'is_active']);
        });

        Schema::table('homepage_experiences', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('homepage_id');
            $table->dropColumn(['sort_order', 'is_active']);
        });

        Schema::table('homepage_expertise_cards', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('homepage_id');
            $table->dropColumn(['sort_order', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::table('homepage_projects', function (Blueprint $table): void {
            $table->foreignId('homepage_id')->nullable()->after('id')->constrained('homepages')->nullOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
        });

        Schema::table('homepage_experiences', function (Blueprint $table): void {
            $table->foreignId('homepage_id')->nullable()->after('id')->constrained('homepages')->nullOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
        });

        Schema::table('homepage_expertise_cards', function (Blueprint $table): void {
            $table->foreignId('homepage_id')->nullable()->after('id')->constrained('homepages')->nullOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
        });

        DB::table('homepage_project_assignments')
            ->orderBy('id')
            ->each(function (object $assignment): void {
                DB::table('homepage_projects')
                    ->where('id', $assignment->homepage_project_id)
                    ->update([
                        'homepage_id' => $assignment->homepage_id,
                        'sort_order' => $assignment->sort_order,
                        'is_active' => $assignment->is_active,
                    ]);
            });

        DB::table('homepage_experience_assignments')
            ->orderBy('id')
            ->each(function (object $assignment): void {
                DB::table('homepage_experiences')
                    ->where('id', $assignment->homepage_experience_id)
                    ->update([
                        'homepage_id' => $assignment->homepage_id,
                        'sort_order' => $assignment->sort_order,
                        'is_active' => $assignment->is_active,
                    ]);
            });

        DB::table('homepage_expertise_card_assignments')
            ->orderBy('id')
            ->each(function (object $assignment): void {
                DB::table('homepage_expertise_cards')
                    ->where('id', $assignment->homepage_expertise_card_id)
                    ->update([
                        'homepage_id' => $assignment->homepage_id,
                        'sort_order' => $assignment->sort_order,
                        'is_active' => $assignment->is_active,
                    ]);
            });

        Schema::dropIfExists('homepage_expertise_card_assignments');
        Schema::dropIfExists('homepage_experience_assignments');
        Schema::dropIfExists('homepage_project_assignments');
    }
};
