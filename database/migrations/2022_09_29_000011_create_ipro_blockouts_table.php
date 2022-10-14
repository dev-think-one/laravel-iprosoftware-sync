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
        Schema::create(config('iprosoftware-sync.tables.blockouts'), function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique()->index();
            $table->unsignedBigInteger('property_id')->index();
            $table->date('check_in')->index()->nullable();
            $table->date('check_out')->index()->nullable();
            $table->text('comments')->nullable();
            $table->string('imported_from_ical')->nullable();
            $table->string('imported_from_channel')->nullable();

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
        Schema::dropIfExists(config('iprosoftware-sync.tables.blockouts'));
    }
};
