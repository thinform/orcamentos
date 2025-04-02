<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('status')->constrained()->onDelete('set null');
        });

        // Atualizar registros existentes com o primeiro usuário
        if (Schema::hasTable('users')) {
            $firstUser = DB::table('users')->first();
            if ($firstUser) {
                DB::table('quotes')->whereNull('user_id')->update(['user_id' => $firstUser->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
