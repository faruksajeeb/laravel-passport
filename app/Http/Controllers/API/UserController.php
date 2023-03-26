<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function loginUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $input = request()->only('email', 'password');
      
        // dd($input);
        if (Auth::attempt($input)) {
            $user = Auth::user();
            // dd($user);
            $data['token_type'] = 'Bearer';
            $data['access_token'] = $user->createToken('example-token')->accessToken;
            $data['user'] = $user;
            return response()->json($data, 200);
        } else {
            return response()->json([
                'errors' => "Email or Password incorrect"
            ], 401);
        }
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $input = request()->only('name','email', 'password');
        $input['password'] = Hash::make($request->password);
        $user = User::create($input);
        Auth::login($user);
        $data['token_type'] = 'Bearer';
        $data['access_token'] = $user->createToken('example-token')->accessToken;
        $data['user'] = $user;
        return response()->json($data, 200);
    } 
    public function userDetail()
    {
        // dd(999);
        // dd(Auth::user());
        $user = Auth::guard('api')->user();
        return Response(['data' => $user], 200);
    }

    public function userLogout(){
        Auth::user()->token()->revoke();
        return response()->json([
            'msg' => 'Sussesfully Logout!'
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
