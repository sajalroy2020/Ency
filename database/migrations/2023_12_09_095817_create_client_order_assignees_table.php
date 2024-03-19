<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_order_assignees', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->unsignedBigInteger('assigned_to');
            $table->unsignedBigInteger('assigned_by');
            $table->boolean('is_active')->default(true);
            $table->string('tenant_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_order_assignees');
    }
};
