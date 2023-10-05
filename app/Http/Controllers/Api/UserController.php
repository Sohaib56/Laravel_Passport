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
    public function loginUser(Request $request)
    {
        //
        $input=$request->all();
        Auth::attempt($input);
        $user=Auth::user();
        $token = $user->createToken('example')->accessToken;
        return Response(['status'=>200,'token'=>$token],200);
        // dd($token);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getUserDetail()
    {
        //
        if (Auth::guard('api')->check()) {
            # code...
            $user=Auth::guard('api')->user();
            return Response(['data'=>$user],200);
        }
        else{
            return Response(['data'=>'UnAuthorized'],400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function logoutUser()
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
