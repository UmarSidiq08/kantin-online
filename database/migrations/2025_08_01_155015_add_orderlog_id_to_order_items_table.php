<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_orderlog_id_to_order_items_table.php

    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('orderlog_id')->nullable()->after('order_id');

            $table->foreign('orderlog_id')->references('id')->on('order_logs')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['orderlog_id']);
            $table->dropColumn('orderlog_id');
        });
    }
};
