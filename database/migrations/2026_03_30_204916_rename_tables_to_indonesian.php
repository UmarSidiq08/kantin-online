<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::rename('orders', 'penjualan');
        Schema::rename('order_items', 'detailpenjualan');
        Schema::rename('menus', 'produk');
    }

    public function down()
    {
        Schema::rename('penjualan', 'orders');
        Schema::rename('detailpenjualan', 'order_items');
        Schema::rename('produk', 'menus');
    }
};
