<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('influencers', function (Blueprint $table) {
            $table->decimal('biaya', 15, 2)->nullable()->after('link_sosmed');
        });
    }

    public function down(): void
    {
        Schema::table('influencers', function (Blueprint $table) {
            $table->dropColumn('biaya');
        });
    }
};
