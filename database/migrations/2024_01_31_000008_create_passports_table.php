<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('passports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('passport_number')->nullable();
            $table->enum('status', ['pending', 'processing', 'ready', 'delivered', 'rejected'])->default('pending');
            $table->date('submission_date');
            $table->date('expiry_date');
            $table->date('issue_date')->nullable();
            $table->date('pickup_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('passports');
    }
};
