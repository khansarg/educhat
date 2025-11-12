<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('materials', function (Blueprint $t) {
            $t->id();
            $t->foreignId('clo_id')->constrained()->cascadeOnDelete();
            $t->string('title');
            $t->longText('content');
            $t->string('source_url')->nullable();
            $t->timestamps();
            $t->index(['clo_id','created_at']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('materials');
    }
};
