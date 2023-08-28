<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->primary(['role_id','user_id']);
            $table->foreignId('user_id')->constrained()
            ->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreignId('role_id')->constrained()
            ->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->timestamps();
        });

        $user = User::first();
        $user = $user->exists() ? $user : factory(User::class)->create(['username' => 'admin']);
        $role = Role::create(['name' => 'admin']);
        $user->roles()->save($role);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_user');
    }
};
