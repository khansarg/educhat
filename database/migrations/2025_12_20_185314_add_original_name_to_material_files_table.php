<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('material_files', function (Blueprint $table) {
        $table->string('original_name')->nullable()->after('material_id');
    });
}

public function down(): void
{
    Schema::table('material_files', function (Blueprint $table) {
        $table->dropColumn('original_name');
    });
}

};
