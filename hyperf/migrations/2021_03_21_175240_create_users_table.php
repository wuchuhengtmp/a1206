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
            $table->string('avatar', 255)->default('')->comment('头像');
            $table->string('nickname', 40)->default('')->comment('昵称');
            $table->float('lat', 10, 6)->default(0)->comment('经度');
            $table->float('lnt', 10, 6)->default(0)->comment('纬度');
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
