<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('visas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['hajj', 'umrah', 'work']);
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->date('submission_date');
            $table->date('approval_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('visa_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visa_id')->constrained()->onDelete('cascade');
            $table->string('document_name');
            $table->string('file_path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('visa_documents');
        Schema::dropIfExists('visas');
    }
};
