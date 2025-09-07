<?php

use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('driver_vehicle', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Driver::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Vehicle::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_vehicle');
    }
};
