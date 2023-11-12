<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('bottle_relationships', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_bottle_id');
            $table->unsignedBigInteger('child_bottle_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bottle_relationships');
    }
};
