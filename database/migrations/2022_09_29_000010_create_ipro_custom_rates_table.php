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
        Schema::create(config('iprosoftware-sync.tables.custom_rates'), function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique()->index();
            $table->unsignedBigInteger('property_id')->index()->nullable();
            $table->date('month')->index()->nullable();
            $table->text('notes')->nullable();
            $table->json('week_price_list')->nullable();
            $table->json('group_size')->nullable();

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
        Schema::dropIfExists(config('iprosoftware-sync.tables.custom_rates'));
    }
};
