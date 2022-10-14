<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('iprosoftware-sync.tables.bookings'), function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique()->index();
            $table->string('external_reservation_id')->nullable()->index();
            $table->unsignedBigInteger('property_id')->nullable()->index();
            $table->unsignedBigInteger('contact_id')->nullable()->index();
            $table->unsignedBigInteger('rep_contact_id')->nullable()->index();
            $table->unsignedBigInteger('booking_status_id')->nullable()->index();
            $table->unsignedBigInteger('brand_id')->nullable()->index();
            $table->string('transaction_id')->nullable()->index();
            $table->date('order_time')->nullable();
            $table->dateTime('modified_time')->nullable();
            $table->date('check_in')->nullable();
            $table->date('check_out')->nullable();
            $table->string('country')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('renter_amount', 8, 2)->nullable();
            $table->decimal('booking_fee', 8, 2)->nullable();
            $table->decimal('booking_fee_vat', 8, 2)->nullable();
            $table->decimal('holiday_extras', 8, 2)->nullable();
            $table->decimal('insurance_total', 8, 2)->nullable();
            $table->decimal('agent_commission', 8, 2)->nullable();
            $table->decimal('discount', 8, 2)->nullable();
            $table->decimal('payment_charges', 8, 2)->nullable();
            $table->decimal('compensation', 8, 2)->nullable();
            $table->decimal('renter_balance', 8, 2)->nullable();
            $table->decimal('commission', 8, 2)->nullable();
            $table->decimal('commission_vat', 8, 2)->nullable();
            $table->decimal('security_deposit', 8, 2)->nullable();
            $table->decimal('renter_total', 8, 2)->nullable();
            $table->decimal('rate_per_day', 8, 2)->nullable();
            $table->json('property_types')->nullable();
            $table->string('status')->nullable();
            $table->string('source')->nullable();
            $table->json('booking_tags')->nullable();
            $table->string('customer_name')->nullable();
            $table->unsignedInteger('adults')->default(0);
            $table->unsignedInteger('children')->default(0);
            $table->unsignedInteger('infants')->default(0);
            $table->unsignedInteger('pets')->default(0);
            $table->json('guests')->nullable();
            $table->json('holiday_extras_ordered')->nullable();
            $table->decimal('deposit', 8, 2)->nullable();
            $table->date('deposit_due_date')->nullable();
            $table->decimal('balance', 8, 2)->nullable();
            $table->date('balance_due_date')->nullable();
            $table->text('guest_notes')->nullable();
            $table->text('house_keeper_notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->json('payment_schedules')->nullable();
            $table->json('payments')->nullable();
            $table->text('holiday_notes')->nullable();
            $table->text('arrival_notes')->nullable();
            $table->text('departure_notes')->nullable();
            $table->json('bills')->nullable();

            $table->dateTime('last_pull_at')->nullable();
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
        Schema::dropIfExists(config('iprosoftware-sync.tables.bookings'));
    }
};
