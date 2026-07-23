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
            $table->string('privacy_contact_email', 254)->nullable()->after('meta_description');
        });
    }

    public function down(): void
    {
        Schema::table('homepages', function (Blueprint $table): void {
            $table->dropColumn('privacy_contact_email');
        });
    }
};
