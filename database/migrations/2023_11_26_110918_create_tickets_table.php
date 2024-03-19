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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('order_id');
            $table->string('ticket_id')->nullable();
            $table->string('ticket_title')->nullable();
            $table->longText('ticket_description');
            $table->unsignedBigInteger('last_reply_id')->nullable();
            $table->unsignedBigInteger('last_reply_by')->nullable();
            $table->timestamp('last_reply_time')->nullable();
            $table->integer('status')->default(TICKET_STATUS_OPEN);
            $table->integer('priority')->default(TICKET_PRIORITY_LOW);
            $table->text('file_id')->nullable();
            $table->string('tenant_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
