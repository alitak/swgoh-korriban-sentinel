<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSnapshotCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('snapshot_caracters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('snapshot_id');
            $table->foreign('snapshot_id')->references('id')->on('snapshots')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('character_id');
            $table->foreign('character_id')->references('id')->on('characters')->onUpdate('cascade')->onDelete('cascade');
            $table->smallInteger('power');
            $table->tinyInteger('rarity');
            $table->tinyInteger('gear_level');
            $table->tinyInteger('relic_tier');
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
        Schema::dropIfExists('snapshot_caracters');
    }
}
