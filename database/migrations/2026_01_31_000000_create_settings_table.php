<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value');
            $table->timestamps();
        });

        // Default: Puskesmas Tutup
        DB::table('settings')->insert(['key' => 'is_open', 'value' => '0']);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
