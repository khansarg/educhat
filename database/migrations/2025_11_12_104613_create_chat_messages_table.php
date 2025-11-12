<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('chat_messages', function (Blueprint $t) {
            $t->id();
            $t->uuid('session_id');
            $t->enum('role', ['user','assistant','system']);
            $t->longText('content');
            $t->json('meta')->nullable();
            $t->timestamps();

            $t->foreign('session_id')->references('id')->on('chat_sessions')->cascadeOnDelete();
            $t->index(['session_id','created_at']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('chat_messages');
    }
};
