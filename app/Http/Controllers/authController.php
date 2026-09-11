<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class authController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function loginSubmit(Request $request)
    {
        // form validation
        $request->validate(
            //rules
            [
                'text_username' => 'required|email',
                'text_password' => 'required|min:6|max:16'
            ],
            //error messages
            [
                'text_username.required' => 'O Username é obrigatório',
                'text_username.email' => 'O Username deve ser um email válido',
                'text_password.required' => 'A Password é obrigatória',
                'text_password.min' => 'A Password deve ter no mínimo :min caracteres',
                'text_password.max' => 'A Password deve ter no máximo :max caracteres'
            ]
        );

        // get user input
        $username = $request->input('text_username');
        $password = $request->input('text_password');

        // check if users exists
        $user = User::where('username', $username)
                    ->where('deleted_at', NULL)
                    ->first();
        
        if (!$user) {
            return redirect()
                    ->back()
                    ->withInput()
                    ->with('loginError', 'Username ou Password incorretos.');
        }

        // check if password is correct
        if(!password_verify($password, $user->password)){
            return redirect()
                    ->back()
                    ->withInput()
                    ->with('loginError', 'Username ou Password incorretos.');
        }

        // update last login
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();

        // login user
        session([
            'user' => [
                'id' => $user->id,
                'username' => $user->username
            ]
        ]);

        echo 'LOGIN COM SUCESSO!';

        // get all users from database
        // $users = User::all()->toArray();

        // $userModel = new User();
        // $users = $userModel->all()->toArray();
        // echo '<pre>';
        // print_r($users);
    }

    public function logout()
    {
        //logout da aplicação
        session()->forget('user');
        return redirect()->to('/login');
    }
}
