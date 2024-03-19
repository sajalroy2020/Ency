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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('customer_id')->default(0);
            $table->string('invoice_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->integer('plan_id')->default(0);
            $table->string('coupon_id')->nullable();
            $table->integer('subscription_id')->default(0);
            $table->dateTime('due_date');
            $table->string('coupon_code')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('setup_fees', 12, 2)->default(0);
            $table->decimal('shipping_charge', 12, 2)->default(0);
            $table->tinyInteger('is_mailed')->default(0);
            $table->tinyInteger('is_recurring')->default(0);
            $table->tinyInteger('payment_status')->default(0);
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
        Schema::dropIfExists('invoices');
    }
};
