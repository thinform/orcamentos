<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && Schema::hasTable('quotes')) {
            $firstUser = DB::table('users')->limit(1)->get();

            if ($firstUser->isNotEmpty()) {
                $userId = (int) $firstUser->first()->id;

                DB::table('quotes')
                    ->whereNull('user_id')
                    ->orWhere('user_id', 0)
                    ->update(['user_id' => $userId]);
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
