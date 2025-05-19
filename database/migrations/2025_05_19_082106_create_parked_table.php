<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Area;
use App\Models\CustomUser;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parked', function (Blueprint $table) {
            $table->id();
            $table->dateTime('in');
            $table->dateTime('out')->nullable();
            $table->string('total_payment')->nullable();
            $table->boolean('payment_status')->default(0);
            $table->foreignIdFor(Area::class);
            $table->foreignIdFor(CustomUser::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parked');
    }
};
