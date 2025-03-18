<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntryController extends Controller
{
    public function createEntry(Request $request)
    {


        $request->validate([
            'valor' => 'required|numeric',
            'user_id' => 'required',
            'origim' => 'required',
        ]);

        $entry = Entry::create([
            'user_id' => $request->user_id,
            'amount' => $request->valor,
            'origim' => $request->origim,
            'entry_date' =>  Carbon::now(),
        ]);

        // Encontrar o usuário
        $user = User::find($request->user_id);

        if ($user) {
            // Incrementar o saldo manualmente
            $user->saldo += $request->valor;
            $user->save(); // Salvar as alterações no banco de dados

            return response()->json($entry, 201);
        } else {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
        User::find($request->user_id)->increment('saldo', $request->valor);
    }

    public function getCurrentMonthEntry(Request $request)
    {
        $entry = Entry::where('user_id',  $request->user_id)->get();

        return response()->json($entry, 200);
    }
}