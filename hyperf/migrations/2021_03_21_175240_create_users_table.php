<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    private $_tableName = 'users';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->_tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username', 255)->comment('用户名');
            $table->string('password', 255)->comment('密码');
            $table->string('role', 20)->default('user')->comment('角色');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->_tableName);
    }
}
