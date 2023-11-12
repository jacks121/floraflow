<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('bottles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('number'); // 瓶身号
            $table->unsignedBigInteger('variety_number'); // 品种号
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bottles');
    }
};
