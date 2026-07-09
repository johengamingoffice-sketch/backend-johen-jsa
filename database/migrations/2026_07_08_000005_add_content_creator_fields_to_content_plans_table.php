<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_plans', function (Blueprint $table) {
            $table->string('take')->nullable()->after('judul');
            $table->string('post')->nullable()->after('take');
            $table->string('days')->nullable()->after('post');
            $table->string('waktu')->nullable()->after('days');
            $table->string('akun')->nullable()->after('waktu');
            $table->string('pic_utama')->nullable()->after('pic');
            $table->string('topic')->nullable()->after('pic_utama');
            $table->string('creator')->nullable()->after('topic');
            $table->string('goals_content')->nullable()->after('creator');
            $table->string('content_pillar')->nullable()->after('goals_content');
            $table->string('type_of_content')->nullable()->after('content_pillar');
            $table->text('reference_content')->nullable()->after('type_of_content');
            $table->text('storyline')->nullable()->after('reference_content');
            $table->text('caption')->nullable()->after('storyline');
            $table->string('revisi')->nullable()->after('caption');
            $table->boolean('acc_to_posting')->default(false)->after('revisi');
        });
    }

    public function down(): void
    {
        Schema::table('content_plans', function (Blueprint $table) {
            $table->dropColumn([
                'take', 'post', 'days', 'waktu', 'akun', 'pic_utama',
                'topic', 'creator', 'goals_content', 'content_pillar', 'type_of_content',
                'reference_content', 'storyline', 'caption', 'revisi', 'acc_to_posting',
            ]);
        });
    }
};
