<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_plans_new', function ($table) {
            $table->id();
            $table->string('posisi');
            $table->string('judul')->nullable();
            $table->string('take')->nullable();
            $table->string('post')->nullable();
            $table->string('days')->nullable();
            $table->string('waktu')->nullable();
            $table->string('akun')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('pic')->nullable();
            $table->string('pic_utama')->nullable();
            $table->string('jenis_desain')->nullable();
            $table->string('topic')->nullable();
            $table->string('creator')->nullable();
            $table->string('goals_content')->nullable();
            $table->string('content_pillar')->nullable();
            $table->string('type_of_content')->nullable();
            $table->text('reference_content')->nullable();
            $table->text('storyline')->nullable();
            $table->text('caption')->nullable();
            $table->string('revisi')->nullable();
            $table->boolean('acc_to_posting')->default(false);
            $table->string('link')->nullable();
            $table->date('deadline')->nullable();
            $table->string('status')->default('plan');
            $table->timestamps();
        });

        $cols = implode(', ', [
            'id', 'posisi', 'judul', 'take', 'post', 'days', 'waktu', 'akun',
            'deskripsi', 'pic', 'pic_utama', 'jenis_desain', 'topic', 'creator',
            'goals_content', 'content_pillar', 'type_of_content', 'reference_content',
            'storyline', 'caption', 'revisi', 'acc_to_posting', 'link', 'deadline',
            'status', 'created_at', 'updated_at'
        ]);

        DB::statement("INSERT INTO content_plans_new ($cols) SELECT $cols FROM content_plans");
        Schema::drop('content_plans');
        Schema::rename('content_plans_new', 'content_plans');
    }

    public function down(): void
    {
        Schema::create('content_plans_old', function ($table) {
            $table->id();
            $table->string('posisi');
            $table->string('judul');
            $table->string('take')->nullable();
            $table->string('post')->nullable();
            $table->string('days')->nullable();
            $table->string('waktu')->nullable();
            $table->string('akun')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('pic')->nullable();
            $table->string('pic_utama')->nullable();
            $table->string('jenis_desain')->nullable();
            $table->string('topic')->nullable();
            $table->string('creator')->nullable();
            $table->string('goals_content')->nullable();
            $table->string('content_pillar')->nullable();
            $table->string('type_of_content')->nullable();
            $table->text('reference_content')->nullable();
            $table->text('storyline')->nullable();
            $table->text('caption')->nullable();
            $table->string('revisi')->nullable();
            $table->boolean('acc_to_posting')->default(false);
            $table->string('link')->nullable();
            $table->date('deadline')->nullable();
            $table->string('status')->default('plan');
            $table->timestamps();
        });

        $cols = implode(', ', [
            'id', 'posisi', 'judul', 'take', 'post', 'days', 'waktu', 'akun',
            'deskripsi', 'pic', 'pic_utama', 'jenis_desain', 'topic', 'creator',
            'goals_content', 'content_pillar', 'type_of_content', 'reference_content',
            'storyline', 'caption', 'revisi', 'acc_to_posting', 'link', 'deadline',
            'status', 'created_at', 'updated_at'
        ]);

        DB::statement("INSERT INTO content_plans_old ($cols) SELECT $cols FROM content_plans");
        Schema::drop('content_plans');
        Schema::rename('content_plans_old', 'content_plans');
    }
};
