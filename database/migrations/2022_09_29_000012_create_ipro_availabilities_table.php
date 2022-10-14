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
        Schema::create(config('iprosoftware-sync.tables.availabilities'), function (Blueprint $table) {
            $table->unsignedBigInteger('property_id')->unique()->index();
            $table->json('availability')->nullable();
            $table->json('day_availability')->nullable();

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
        Schema::dropIfExists(config('iprosoftware-sync.tables.availabilities'));
    }
};
