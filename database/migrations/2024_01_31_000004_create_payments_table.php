<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'completed', 'failed']);
            $table->string('payment_method');
            $table->string('transaction_id')->nullable();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->timestamp('issue_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('payments');
    }
};
