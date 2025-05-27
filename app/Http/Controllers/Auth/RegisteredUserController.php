<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

public function store(Request $request)
{
    \Log::info('Iniciando registro', $request->all());

    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
        'perfil' => ['required', 'in:admin,operador,visualizacao'],
	'password' => ['required', 'confirmed', 'min:3'],
    ]);

    try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'perfil' => $request->perfil,
            'password' => Hash::make($request->password),
        ]);

        \Log::info('Usuário criado com ID: ' . $user->id);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('dashboard');
    } catch (\Throwable $e) {
        \Log::error('Erro ao criar usuário: ' . $e->getMessage());
        return back()->withErrors(['erro' => 'Falha ao registrar usuário. Detalhes no log.']);
    }
}
}
