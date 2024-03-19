<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->name = 'neni';
        $user->email = 'neni28@gmail.com';
        $user->phone = "08135565428";
        $user->password = Hash::make('admin123');
        $user->role = 'admin';
        $user->status = 'aktif';
        $user->save();
    }
}
