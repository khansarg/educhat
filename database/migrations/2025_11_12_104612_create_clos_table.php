<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('clos', function (Blueprint $t) {
            $t->id();
            $t->foreignId('course_id')->constrained()->cascadeOnDelete();
            $t->string('code'); // ex: CLO-1
            $t->string('title');
            $t->text('outcome_text');
            $t->json('tags')->nullable();
            $t->timestamps();
            $t->unique(['course_id','code']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('clos');
    }
};
