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
        Schema::create(config('iprosoftware-sync.tables.properties'), function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique()->index();
            $table->string('property_reference')->nullable()->index();
            $table->unsignedBigInteger('owner_company_id')->nullable()->index();
            $table->unsignedBigInteger('owner_contact_id')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('property_name')->nullable();
            $table->string('title')->nullable();
            $table->string('owner_property_name')->nullable();
            $table->boolean('suspended')->default(false);
            $table->boolean('withdrawn')->default(false);
            $table->boolean('hide_on_website')->default(false);
            $table->boolean('disable_online_booking')->default(false);
            $table->string('contract_renewal_date')->nullable();
            $table->text('property_website')->nullable();
            $table->text('url')->nullable();
            $table->string('currency')->nullable();
            $table->string('currency_iso')->nullable();
            $table->boolean('hide_rates')->default(false);
            $table->decimal('min_rate', 8, 2)->nullable();
            $table->decimal('max_rate', 8, 2)->nullable();
            $table->boolean('rates_include_vat')->default(false);
            $table->decimal('commission', 8, 2)->nullable();
            $table->decimal('breakages_deposit', 8, 2)->nullable();
            $table->longText('availability_notes')->nullable();
            $table->longText('intro')->nullable();
            $table->longText('main_description')->nullable();
            $table->longText('region_description')->nullable();
            $table->longText('location_description')->nullable();
            $table->longText('warnings')->nullable();
            $table->string('rental_notes_title')->nullable();
            $table->longText('rental_notes')->nullable();
            $table->string('rental_notes_title_1')->nullable();
            $table->longText('rental_notes_1')->nullable();
            $table->string('virtual_tour_title')->nullable();
            $table->longText('virtual_tour')->nullable();
            $table->string('address')->nullable();
            $table->string('address_2')->nullable();
            $table->string('city')->nullable();
            $table->string('county')->nullable();
            $table->string('postcode')->nullable();
            $table->string('country')->nullable();
            $table->string('geolocation')->nullable();
            $table->json('location')->nullable();
            $table->longText('pros')->nullable();
            $table->longText('cons')->nullable();
            $table->string('build_size')->nullable();
            $table->string('plot_size')->nullable();
            $table->string('licence')->nullable();
            $table->string('trust_pilot_tag')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_keywords')->nullable();
            $table->text('seo_description')->nullable();
            $table->json('property_attributes')->nullable();
            $table->json('rooms')->nullable();
            $table->json('distances')->nullable();
            $table->json('reviews')->nullable();
            $table->json('assigned_contacts')->nullable();
            $table->text('floor_plan')->nullable();
            $table->text('press')->nullable();
            $table->json('images')->nullable();
            $table->json('rates')->nullable();
            $table->json('availabilities')->nullable();
            $table->json('holiday_extras')->nullable();
            $table->json('discounts')->nullable();

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
        Schema::dropIfExists(config('iprosoftware-sync.tables.properties'));
    }
};
