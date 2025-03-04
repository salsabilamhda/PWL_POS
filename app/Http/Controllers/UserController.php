<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        /*$data = [
            'level_id' => 2,
            'username' => 'manager_lima',
            'nama' => 'Manager 5',
            'password' => Hash::make('12345')
        ];

        UserModel::create($data);*/

        $jumlah_data = UserModel::where('level_id', '2')->count();
        return view('user', ['jumlah_data' => $jumlah_data]);

        //return view('user', ['data' => $user]);
    }
}