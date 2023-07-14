<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
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

    public function register(Request $request)
    {
    
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'email' => 'required|unique:users',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $email = $request->email;
        $name = $request->name;
        $password = $request->password;

        $checkUser = User::where('email', $email)->first();
        if($checkUser){
            return response()->json([
                'message' => 'Register Error, Email exist',
                'status' => 400,
                'data' => []
            ]);
        }
        
        $userRegister = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        if($userRegister){
            return response()->json([
                'message' => 'Register Success',
                'data'   => $userRegister,

            ]);
        }
        return response()->json([
            'message' => 'Register Error',
            'data'    =>  [],
            'status'  => 500
        ]);

    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    // public function login (Request $request) {
    //     $validator = Validator::make($request->all(), [
    //         'email'=> 'email|required|min:8',
    //         'password' => 'required|min:6'
    //     ]);


    //     if($validator->fails()) {
    //         $arrRes = [
    //             'errCode'=> 1,
    //             'message' => 'Lỗi validate dữ liệu',
    //             'data' => $validator->errors()
    //         ];

    //         return response()->json($arrRes, 402);
    //     }
    //     $credentials = [
    //         'email' => $request->email,
    //         'password' => $request->password
    //     ];

    //         $myTTL = 2000;
    //         JWTAuth::factory()->setTTL($myTTL);
    //         if(!$token = JWTAuth::attempt($credentials , ['exp' => \Carbon\Carbon::now()->addDays(7)->timestamp] )) {
    //             $arrRes = [
    //                 'errCode'=> 2,
    //                 'message' => 'Vui lòng kiểm tra email và mật khẩu',
    //                 'data' => []
    //             ];
    //             return response()->json($arrRes, 201);
    //         }

    //         if(auth()->user()->active === 0){
    //             $arrRes = [
    //                 'errCode'=> 2,
    //                 'message' => 'Bạn đã bị khóa tài khoản',
    //                 'data' => []
    //             ];
    //             return response()->json($arrRes, 201);
    //         }

    //         // auth()->login($token);
    //         $user_id = auth()->setToken($token)->user()->id;
    //         $user = User::findOrFail($user_id);
    //         $user->avatar = strstr($user->avatar, "http") != false  ? $user->avatar :(env('APP_URL', 'http://localhost:8080').$user->avatar);
            
    //         $arrRes = [
    //             'errCode'=> 0,
    //             'message' => 'Đăng nhập thành công',
    //             'data' => [
    //                 "user" => $user,
    //                 'token' => $token
    //             ]
    //         ];
    //         return response()->json($arrRes, 201);
            

    // }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
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

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $myTTL = 2000;
        JWTAuth::factory()->setTTL($myTTL);
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            "message" => "Đăng nhập thành công",
            "user" =>   auth()->user(),
            "redirect_url" => '/', // Đường dẫn đến trang chủ
        ]);
    }
}