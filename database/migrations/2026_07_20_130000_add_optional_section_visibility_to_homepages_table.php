<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homepages', function (Blueprint $table): void {
            $table->boolean('show_expertise_section')->default(true)->after('expertise_title');
            $table->boolean('show_experience_section')->default(true)->after('experience_description');
        });
    }

    public function down(): void
    {
        Schema::table('homepages', function (Blueprint $table): void {
            $table->dropColumn(['show_expertise_section', 'show_experience_section']);
        });
    }
};
