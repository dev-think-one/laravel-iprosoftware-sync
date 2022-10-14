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
        Schema::create(config('iprosoftware-sync.tables.contacts'), function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique()->index();
            $table->unsignedBigInteger('type_id')->nullable()->index();
            $table->unsignedBigInteger('brand_id')->nullable()->index();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('company_name')->nullable();
            $table->string('title', 50)->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('email_alt')->nullable();
            $table->string('email_alt_1')->nullable();
            $table->string('telephone')->nullable();
            $table->string('telephone_alt')->nullable();
            $table->string('mobile')->nullable();
            $table->string('address')->nullable();
            $table->string('street_name')->nullable();
            $table->string('town_city')->nullable();
            $table->string('county_area')->nullable();
            $table->string('postcode')->nullable();
            $table->string('country')->nullable();
            $table->string('country_code')->nullable();
            $table->string('language', 50)->nullable();
            $table->boolean('contact_by_post')->default(false);
            $table->boolean('contact_by_email')->default(false);
            $table->boolean('contact_by_phone')->default(false);
            $table->boolean('contact_by_sms')->default(false);
            $table->boolean('subscribed_to_mailing_list')->default(false);
            $table->text('comments')->nullable();
            $table->decimal('commission', 8, 2)->nullable();
            $table->decimal('balance', 8, 2)->nullable();
            $table->string('retainer')->nullable();

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
        Schema::dropIfExists(config('iprosoftware-sync.tables.contacts'));
    }
};
