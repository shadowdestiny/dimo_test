<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['username', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        // verificar porque no llega a este metodo desde el routing
        return JWTAuth::parseToken()->authenticate();
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function recovery()
    {
        // enviar un token al correo que vaya a la siguiente ruta /recovery/{token}
        // ejemplo : /recovery/asd6fas4d6f5s4df6s5dfasd6fas4d6f54df
        return response()->json(['message' => 'Se ha enviado un código a su correo electrónico'], 200);
    }

    public function confirm_password(Request $request)
    {
        /*
        $request->token;
        $request->password;
        */

        // validar aqui el token entrante de la contraseña y la nueva contraseña entrante

        return response()->json(['message' => 'La contraseña se ha cambiado con exito'], 200);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => /*auth()->factory()->getTTL() **/ 60,
        ]);
    }
}
