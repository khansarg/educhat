<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('chat_sessions', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('course_id')->constrained()->cascadeOnDelete();
            $t->foreignId('clo_id')->constrained()->cascadeOnDelete();
            $t->json('meta')->nullable();
            $t->timestamps();
            $t->index(['user_id','course_id','clo_id','created_at']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('chat_sessions');
    }
};
