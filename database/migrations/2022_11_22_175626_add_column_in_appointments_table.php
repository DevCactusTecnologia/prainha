<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->date('delivery_date')->nullable();
            $table->string('fast')->nullable();
            $table->string('urgency')->nullable();
            $table->date('dum')->nullable();
            $table->string('ins_company')->nullable();
            $table->string('guide_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('delivery_date');
            $table->dropColumn('fast');
            $table->dropColumn('urgency');
            $table->dropColumn('dum');
            $table->dropColumn('ins_company');
            $table->dropColumn('guide_number');
        });
    }
}
