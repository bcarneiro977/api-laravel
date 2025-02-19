<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('address_id')->after('customer_id');
            $table->uuid('payment_id')->nullable()->after('address_id');
            $table->decimal('total_price', 10, 2)->after('payment_id');
            $table->enum('status', ['pending', 'paid', 'shipped', 'delivered', 'canceled'])
                  ->default('pending')->after('total_price');

            // Adicionando as novas chaves estrangeiras
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            $table->dropForeign(['payment_id']);
            $table->dropColumn(['address_id', 'payment_id', 'total_price', 'status']);
        });
    }
};