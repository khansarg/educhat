<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            if (Schema::hasColumn('chat_messages', 'role')) {
                $table->renameColumn('role', 'sender');
            }

            if (Schema::hasColumn('chat_messages', 'content')) {
                $table->renameColumn('content', 'message');
            }
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            // optional rollback
        });
    }
};
