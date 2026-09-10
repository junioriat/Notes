<?php

namespace App\Http\Controllers;

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

        // test database connection
        try {
            DB::connection()->getPdo();
            echo "CONECTADO COM SUCESSO";
        } catch (\PDOException $e) {
            echo "ERRO AO CONECTAR COM O BANCO DE DADOS: " . $e->getMessage();
        }
    }

    public function logout()
    {
        echo 'logout';
    }
}
