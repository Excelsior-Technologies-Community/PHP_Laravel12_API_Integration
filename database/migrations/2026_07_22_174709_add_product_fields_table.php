<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->nullable()->after('name');
            $table->string('category')->nullable()->after('description');
            $table->string('image')->nullable()->after('stock');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('image');
            $table->text('features')->nullable()->after('status');
            $table->decimal('discount_price', 10, 2)->nullable()->after('features');
            $table->integer('min_order_qty')->default(1)->after('discount_price');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sku', 'category', 'image', 'status', 'features', 'discount_price', 'min_order_qty']);
        });
    }
};
