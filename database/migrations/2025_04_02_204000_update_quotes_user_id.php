<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('users') && Schema::hasTable('quotes')) {
            $firstUser = DB::table('users')->first();
            
            if ($firstUser) {
                DB::table('quotes')
                    ->whereNull('user_id')
                    ->orWhere('user_id', 0)
                    ->update(['user_id' => $firstUser->id]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('quotes')) {
            DB::table('quotes')
                ->whereNotNull('user_id')
                ->update(['user_id' => null]);
        }
    }
};
