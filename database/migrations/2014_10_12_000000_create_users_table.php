<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
<<<<<<< HEAD
            $table->string('firstName');
            $table->string('lastName');
            $table->string('phoneNumber')->nullable();
            $table->date('dateOfBirth');
            $table->enum('role', ['business', 'customer']);
=======
            $table->string('name');
>>>>>>> 1128fdfcda7eb849a5bb998630ee1c410bd7a1d8
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
