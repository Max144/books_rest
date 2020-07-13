<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register user
     *
     * @OA\Post(
     *     path="/api/auth/register",
     *     operationId="auth.register",
     *     tags={"AuthController"},
     *     description="user register",
     *     @OA\Parameter(
     *          name="name",
     *          description="name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="email",
     *          description="Email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="password",
     *          description="Password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="password_confirmation",
     *          description="Password confirmation",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="successful register",
     *          @OA\JsonContent(),
     *       ),
     *     @OA\Response(
     *          response=401,
     *          description="wrong data provided",
     *          @OA\JsonContent(),
     *       ),
     * )
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create(array_merge(
            $request->only('name', 'email'),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'You were successfully registered. Use your email and password to sign in.'
        ], 200);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param Request $request
     *
     * @OA\Post(
     *     path="/api/auth/login",
     *     operationId="auth.login",
     *     tags={"AuthController"},
     *     description="user login",
     *     @OA\Parameter(
     *          name="email",
     *          description="Email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="password",
     *          description="Password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="successful login",
     *          @OA\JsonContent(),
     *       ),
     *     @OA\Response(
     *          response=401,
     *          description="wrong data provided",
     *          @OA\JsonContent(),
     *       ),
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'You cannot sign with those credentials',
                'errors' => 'Unauthorised'
            ], 401);
        }

        $token = Auth::user()->createToken(config('app.name'));
//        $token->token->expires_at = $request->remember_me ?
//            Carbon::now()->addMonth() :
//            Carbon::now()->addDay();
//
//        $token->token->save();

        return response()->json([
            'status' => 'success'
        ], 200)->header('Authorization', $token->accessToken);
    }

    /**
     * logout
     *
     * @OA\post(
     *     path="/api/auth/logout",
     *     operationId="auth.logout",
     *     tags={"AuthController"},
     *     description="user logout",
     *     @OA\Response(
     *          response=200,
     *          description="logged out",
     *          @OA\JsonContent(),
     *       ),
     *     security={ {"bearer": {}} },
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'You are successfully logged out',
        ]);
    }
}
