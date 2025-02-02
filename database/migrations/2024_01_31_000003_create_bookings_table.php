<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->enum('service_type', ['bus', 'car']);
            $table->dateTime('booking_date');
            $table->string('location');
            $table->decimal('cost', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled']);
            $table->timestamps();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('ticket_number')->unique();
            $table->timestamp('issue_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('bookings');
    }
};
