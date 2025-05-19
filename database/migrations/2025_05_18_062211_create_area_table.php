<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ParkingRate;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('area', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('parking_space_normal');
            $table->json('parking_space_normal_user')->nullable();
            $table->string('parking_space_oku');
            $table->json('parking_space_oku_user')->nullable();
            $table->foreignIdFor(ParkingRate::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('area');
    }
};
