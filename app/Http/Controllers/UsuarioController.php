<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\LogHelper;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('busca')) {
            $busca = $request->input('busca');
            $query->where(function ($q) use ($busca) {
                $q->where('name', 'like', "%$busca%")
                  ->orWhere('email', 'like', "%$busca%")
                  ->orWhere('perfil', 'like', "%$busca%");
            });
        }

        $usuarios = $query->paginate(10);
        return view('usuarios.index', compact('usuarios'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
	public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'perfil' => 'required|in:admin,operador,visualizacao',
        'password' => 'required|string|min:6',
    ]);

    $usuario = \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'perfil' => $request->perfil,
        'password' => Hash::make($request->password),
    ]);

    if ($request->has('divisoes')) {
        foreach ($request->divisoes as $divisao) {
            \DB::table('divisao_user')->insert([
                'user_id' => $usuario->id,
                'divisao' => $divisao,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
        LogHelper::registrar('created', 'Usuário', 'Usuário criado: ' . $usuario->name);

    return redirect()->route('usuarios.index')->with('success', 'Usuário criado com sucesso.');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $usuario = \App\Models\User::findOrFail($id);

        $divisoes = \DB::table('divisao_user')
            ->where('user_id', $usuario->id)
            ->pluck('divisao')
            ->toArray();

        return view('usuarios.edit', compact('usuario', 'divisoes'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $usuario = \App\Models\User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $usuario->id,
            'perfil' => 'required|in:admin,operador,visualizacao',
        ]);

        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
            'perfil' => $request->perfil,
        ]);

        // Atualiza divisões se perfil for 'visualizacao'
        \DB::table('divisao_user')->where('user_id', $usuario->id)->delete();

        if ($request->perfil === 'visualizacao' && $request->has('divisoes')) {
            foreach ($request->divisoes as $divisao) {
                \DB::table('divisao_user')->insert([
                    'user_id' => $usuario->id,
                    'divisao' => $divisao,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

	LogHelper::registrar('updated', 'Usuário', 'Usuário atualizado: ' . $usuario->name);

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $usuario = \App\Models\User::findOrFail($id);

        if (auth()->id() === $usuario->id) {
            return redirect()->route('usuarios.index')->with('error', 'Você não pode excluir a si mesmo.');
        }

        $usuario->delete();

	LogHelper::registrar('deleted', 'Usuário', 'Usuário excluído: ' . $usuario->name);

        return redirect()->route('usuarios.index')->with('success', 'Usuário excluído com sucesso.');
    }
}
