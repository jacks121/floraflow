<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('status_changes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bottle_id');
            $table->enum('status', ['in_stock', 'infected', 'planted', 'destroyed', 'sold']);
            $table->date('change_date');
            $table->string('user_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('status_changes');
    }
};
