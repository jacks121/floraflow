<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('varieties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('variety_number')->unique(); // 品种号，唯一
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('varieties');
    }
};
