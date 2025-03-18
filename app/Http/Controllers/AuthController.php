<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {


            return response()->json([
                'message' => 'Autenticação bem-sucedida',
                'user' => Auth::user()
            ]);
        }

        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }


    /**
 * Registra um novo usuário no sistema
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function register(Request $request)
{
    // return $request->all();
    $request->validate([
        'name' => 'required|string|min:3',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6'
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password)
    ]);

    Auth::login($user);

    return response()->json([
        'message' => 'Usuário registrado com sucesso',
        'user' => Auth::user()
    ], 201);
}

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    public function user(Request $request)
    {
        return response()->json(Auth::user());
    }

    /**
 * Envia um link de redefinição de senha para o email informado
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function forgotPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    // Gerar token de redefinição de senha
    $token = Str::random(64);

    // Salvar token no banco de dados
    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $request->email],
        [
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now()
        ]
    );

    // Enviar email com link de redefinição de senha
    try {
        Mail::send('emails.reset-password', ['token' => $token, 'email' => $request->email], function($message) use ($request) {
            $message->to($request->email);
            $message->subject('Link de Redefinição de Senha');
        });

        return response()->json([
            'message' => 'Email de redefinição de senha enviado com sucesso',
            'success' => true
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Erro ao enviar email de redefinição de senha',
            'error' => $e->getMessage(),
            'success' => false
        ], 500);
    }
}

/**
 * Redefine a senha do usuário com o token informado
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users',
        'password' => 'required|string|min:6|confirmed',
        'token' => 'required'
    ]);

    // Verificar se o token é válido
    $resetRecord = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->first();

    if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
        return response()->json([
            'message' => 'Token inválido',
            'success' => false
        ], 400);
    }

    // Verificar se o token não expirou (24 horas)
    $tokenCreatedAt = Carbon::parse($resetRecord->created_at);
    if (now()->diffInHours($tokenCreatedAt) > 24) {
        return response()->json([
            'message' => 'Token expirado',
            'success' => false
        ], 400);
    }

    // Atualizar senha do usuário
    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    // Remover token de redefinição de senha
    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    return response()->json([
        'message' => 'Senha redefinida com sucesso',
        'success' => true
    ]);
}

/**
 * Envia um código OTP para o email informado
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function sendOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    // Gerar OTP de 6 dígitos
    $otp = rand(100000, 999999);

    // Salvar OTP no banco de dados
    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $request->email],
        [
            'email' => $request->email,
            'token' => Hash::make($otp),
            'created_at' => now()
        ]
    );

    // Enviar email com OTP
    try {
        Mail::send('emails.otp', ['otp' => $otp, 'email' => $request->email], function($message) use ($request) {
            $message->to($request->email);
            $message->subject('Código de Verificação para Recuperação de Senha');
        });

        return response()->json([
            'message' => 'Código de verificação enviado com sucesso',
            'email' => $request->email,
            'success' => true
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Erro ao enviar código de verificação',
            'error' => $e->getMessage(),
            'success' => false
        ], 500);
    }
}

/**
 * Verifica o código OTP
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function verifyOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'otp' => 'required|string|min:6|max:6',
    ]);

    // Verificar se o OTP é válido
    $resetRecord = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->first();

    if (!$resetRecord) {
        return response()->json([
            'message' => 'Nenhum código de verificação encontrado para este email',
            'success' => false
        ], 400);
    }

    // Verificar se o OTP não expirou (10 minutos)
    $tokenCreatedAt = Carbon::parse($resetRecord->created_at);
    if (now()->diffInMinutes($tokenCreatedAt) > 10) {
        return response()->json([
            'message' => 'Código de verificação expirado',
            'success' => false
        ], 400);
    }

    // Verificar se o OTP está correto
    if (!Hash::check($request->otp, $resetRecord->token)) {
        return response()->json([
            'message' => 'Código de verificação inválido',
            'success' => false
        ], 400);
    }

    // Gerar token para redefinição de senha
    $token = Str::random(64);

    // Atualizar token no banco de dados
    DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->update([
            'token' => Hash::make($token),
            'created_at' => now()
        ]);

    return response()->json([
        'message' => 'Código de verificação validado com sucesso',
        'token' => $token,
        'email' => $request->email,
        'success' => true
    ]);
}

/**
 * Redefine a senha do usuário após verificação OTP
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function resetPasswordWithToken(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users',
        'token' => 'required|string',
        'password' => 'required|string|min:6|confirmed'
    ]);

    // Verificar se o token é válido
    $resetRecord = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->first();

    if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
        return response()->json([
            'message' => 'Token inválido',
            'success' => false
        ], 400);
    }

    // Verificar se o token não expirou (30 minutos)
    $tokenCreatedAt = Carbon::parse($resetRecord->created_at);
    if (now()->diffInMinutes($tokenCreatedAt) > 30) {
        return response()->json([
            'message' => 'Token expirado',
            'success' => false
        ], 400);
    }

    // Atualizar senha do usuário
    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    // Remover token de redefinição de senha
    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    return response()->json([
        'message' => 'Senha redefinida com sucesso',
        'success' => true
    ]);
}

/**
 * Busca os dados do usuário
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\JsonResponse
 */
public function getUser($id)
{
    try {
        $user = User::findOrFail($id);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Usuário não encontrado',
            'error' => $e->getMessage()
        ], 404);
    }
}

/**
 * Atualiza os dados do usuário
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\JsonResponse
 */
public function updateUser(Request $request, $id)
{
    try {
        $user = User::findOrFail($id);

        // Validar dados
        $rules = [
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        // Se o usuário estiver tentando alterar a senha
        if ($request->has('current_password')) {
            $rules['current_password'] = 'required';
            $rules['new_password'] = 'required|min:6';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verificar se a senha atual está correta
        if ($request->has('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'message' => 'Senha atual incorreta'
                ], 422);
            }

            // Atualizar senha
            $user->password = Hash::make($request->new_password);
        }

        // Atualizar dados
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'message' => 'Perfil atualizado com sucesso',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Erro ao atualizar perfil',
            'error' => $e->getMessage()
        ], 500);
    }
}
}