<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->enum('service_type', ['visa', 'booking']);
            $table->enum('status', ['pending', 'completed', 'failed']);
            $table->decimal('amount', 10, 2);
            $table->string('reference_id')->unique();
            $table->text('description')->nullable();
            $table->string('transactionable_type');
            $table->unsignedBigInteger('transactionable_id');
            $table->timestamps();

            $table->index(['transactionable_type', 'transactionable_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
