<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->date('eventdate');
            $table->string('revision')->nullable();
            $table->string('dc')->nullable();
            $table->string('checkinutc', 20)->nullable();
            $table->string('checkoututc', 20)->nullable();
            $table->string('activity', 50);
            $table->string('activityremark');
            $table->string('fromstn', 20);
            $table->string('stdutc', 20);
            $table->string('tostn', 20);
            $table->string('stautc', 20);
            $table->string('achotel')->nullable();
            $table->string('blockhours')->nullable();
            $table->string('flighttime', 20)->nullable();
            $table->string('nighttime')->nullable();
            $table->string('duration', 20)->nullable();
            $table->string('ext')->nullable();
            $table->string('paxbooked')->nullable();
            $table->string('acreg')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('roster_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
