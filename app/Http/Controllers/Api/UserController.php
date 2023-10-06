<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function registerUser(Request $request){
        // dd($request->all());
        $validatedData=$request->validate([
            'name'=>'required',
            'email'=>['required','email'],
            'password'=>['min:8','confirmed']
        ]);
        $user=User::create($validatedData);
        $token = $user->createToken('auth_token')->accessToken;
        return response()->json([
            'token'=>$token,
            'user'=>$user,
            'message'=>'User Created Succesfully',
            'status'=>200,
        ]);
    }
    public function loginUser(Request $request)
    {
        //
        $validatedData=$request->validate([
            'email'=>['required'],
            'password'=>['required']
        ]);
        Auth::attempt($validatedData);
        $user=Auth::user();
        $token = $user->createToken('auth_token')->accessToken;
        return response()->json([
            'token'=>$token,
            'user'=>$user,
            'message'=>'User LogedIn Succesfully',
            'status'=>200,
        ]);
        // $input=$request->all();
        // Auth::attempt($input);
        // $user=Auth::user();
        // $token = $user->createToken('example')->accessToken;
        // return Response(['status'=>200,'token'=>$token],200);
        // dd($token);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getUserDetail($id)
{
    if (Auth::guard('api')->check()) {
        $user = Auth::guard('api')->user();
        // Now you can use $user to retrieve the user details or perform other actions.
        return response()->json([
            'user' => $user,
            'message' => 'User Found',
            'status' => 200,
        ]);
    } else {
        return response()->json([
            'user' => null,
            'message' => 'User Not Found',
            'status' => 401,
        ]);
    }
}

    // public function getUserDetail($id)
    // {
    //     //
    //     if (Auth::guard('api')->check()) {
    //     $user=Auth::guard('api')->User::find($id);
    //     return response()->json([
    //         'user'=>$user,
    //         'message'=>'User Found',
    //         'status'=>200,
    //     ]);
    // }

        // if(is_null($user)){
        //     return response()->json([
        //         'user'=>Null,
        //         'message'=>'User Not Found',
        //         'status'=>401,
        //     ]);
        // }
        // else{
            // return response()->json([
            //     'user'=>$user,
            //     'message'=>'User Found',
            //     'status'=>200,
            // ]);
        // }
    //     if (Auth::guard('api')->check()) {
    //         # code...
    //         $user=Auth::guard('api')->user();
    //         return Response(['data'=>$user],200);
    //     }
    //     else{
    //         return Response(['data'=>'UnAuthorized'],400);
    //     }
 

    /**
     * Display the specified resource.
     */
    public function logoutUser($id)
    {
        //
        if(Auth::guard('api')->check()) {
        $accesstoken=Auth::guard('api')->user()->token();
        \DB::table('oauth_refresh_tokens')
        ->where('access_token_id',$accesstoken->id)
        ->update(['revoked'=>true]);
        $accesstoken->revoke();
        return Response(['data'=>'UnAuthorized','message'=>'Successfully Logout'],200);
    }
    else{
        return Response(['data'=>'UnAuthorized'],400);
    }
}

    }
