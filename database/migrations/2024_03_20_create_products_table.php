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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('cost_price', 10, 2);
            $table->decimal('profit_margin', 5, 2)->default(15.00);
            $table->decimal('sale_price', 10, 2);
            $table->decimal('installment_fee', 5, 2)->default(18.00);
            $table->decimal('installment_price', 10, 2);
            $table->string('brand')->default('THINFORMA');
            $table->string('supplier')->default('Google');
            $table->string('unit')->default('UN');
            $table->decimal('height', 10, 2)->nullable()->default(0);
            $table->decimal('width', 10, 2)->nullable()->default(0);
            $table->decimal('depth', 10, 2)->nullable()->default(0);
            $table->decimal('weight', 10, 2)->nullable()->default(0);
            $table->string('supplier_link')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('category_id')->constrained();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
}; 