<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('name',64);
            $table->string('surname',64);
            $table->string('middlename',64);
            $table->integer('user_role_id');
            $table->foreign('user_role_id')->references('id')->on('user_roles')->onUpdate('cascade')->onDelete('cascade');;
            $table->index('user_role_id');
            $table->string('email',64)->unique();
            $table->string('password',128);
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone',32)->unique();;
            $table->string('phone_access_time',128)->nullable();
            $table->integer('user_status_id');
            $table->foreign('user_status_id')->references('id')->on('user_status')->onUpdate('cascade')->onDelete('cascade');;
            $table->index('user_status_id');
            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
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
