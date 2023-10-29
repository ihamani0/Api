<?php

namespace App\Http\Controllers;

use App\Events\VerifyEmailCode;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(RegisterRequest $request)
    {
        //validation Rule
        $user = $request->validated();
        //Hash Password
        $user['password'] = Hash::make($request->password);
        //1--- generate random number
        //
        $user['cod_Mobile'] = rand(00000, 99999);
        //
        $user['cod_email'] = rand(00000, 99999);
        //register user in data base


        $user = User::create($user);

        //test if user register and exists or not


        if ($user) {
            $credentials = $request->only(['email', 'password']);

            return $this->login($credentials);
        } else {
            return response()->json(['message' => 'Please Refresh and try again']);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(array $user = null)
    {

        $user ? $credentials = $user  : $credentials = request(['email', 'password']);

        $token = auth('api')->attempt($credentials);


        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        //check if Email verified or not

        auth('api')->user()->email_verified_at == null;

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function ResendActiveCode()
    {
        event(new VerifyEmailCode(auth('api')->user()));
    }

    public function ActiveCode(Request $request){

        $request->validate([
            'code' => 'required'
        ]);

        if($request->code == auth('api')->user()->cod_email)
        {
            auth('api')->user()->cod_email = null;
            auth('api')->user()->email_verified_at = now();
            auth('api')->user()->save();
            
            return response()->json(['message'=> "Active Successfully"]);
        }else{
            return response()->json(['Error'=> "Cod Incorrect"]);
        }

    }
}
