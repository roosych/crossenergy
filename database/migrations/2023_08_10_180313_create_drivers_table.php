<?php

use App\Models\Owner;
use App\Models\VehicleType;
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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();

            $table->string('fullname');
            $table->unsignedBigInteger('number')->nullable();
            $table->string('phone');

            $table->foreignIdFor(VehicleType::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignIdFor(Owner::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->boolean('availability')->default(1);
            $table->boolean('dnu')->default(0);

            $table->string('capacity')->nullable();
            $table->string('dimension')->nullable();

            $table->string('citizenship')->nullable();

            $table->string('zipcode')->nullable();
            $table->string('location')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            $table->string('future_zipcode')->nullable();
            $table->string('future_location')->nullable();
            $table->string('future_latitude')->nullable();
            $table->string('future_longitude')->nullable();
            $table->dateTime('future_datetime')->nullable();

            $table->mediumText('note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
