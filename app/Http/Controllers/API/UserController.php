<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
// use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponseHelper;
use App\Http\Requests\User\RegisterRequest;

class UserController extends Controller
{
    use ApiResponseHelper;


    public function login(Request $request)

    {
        $validator = Validator::make($request->all(), [
            'key'    => 'required',
            'password' => 'required'
        ]);

        $loginValue = request('key');
        $username = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
        $merge = request()->merge([$username => $loginValue, 'password' => $request->password]);

        if ($validator->fails()) {
            return  $this->setCode(422)->setData(null)->setMessage($validator->errors()->first())->send();
        }
        $credentials = $request->only($username, 'password');

        if (Auth::attempt($credentials)) {
            $user             = Auth::user();
            $success['token'] = $user->createToken('accessToken')->accessToken;
            return  $this->setCode(200)->setData(["user" => $user, "token" => $success['token']])->setMessage('You are successfully logged in.')->send();
        } else {
            return  $this->setCode(400)->setData(null)->setMessage('Unauthorised', ['error' => 'Unauthorised'])->send();
        }
    }

    public function register(RegisterRequest $request)

    {
        $validatorPhone = User::where("email", $request->email)->count();
        if ($validatorPhone > 0) {
            return  $this->setCode(405)->setData(null)->setMessage('email is unique ')->send();
        }
        $validatorPhone = User::where("mobile", $request->mobile)->count();
        if ($validatorPhone > 0) {
            return  $this->setCode(405)->setData(null)->setMessage('mobile is unique ')->send();
        }
        //save the data to the database
        $user  = new user;
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->save();
        $token = $user->createToken('APPLICATION')->accessToken;
        return  $this->setCode(200)->setData(["user" => $user, "token" => $token])->setMessage('seccess')->send();
    }

    public function getUserDetail(): Response
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            return Response(['data' => $user], 200);
        }
        return Response(['data' => 'Unauthorized'], 401);
    }

    public function userLogout(): Response
    {
        if (Auth::guard('api')->check()) {
            $accessToken = Auth::guard('api')->user()->token();

            \DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $accessToken->id)
                ->update(['revoked' => true]);
            $accessToken->revoke();

            return Response(['data' => 'Unauthorized', 'message' => 'User logout successfully.'], 200);
        }
        return Response(['data' => 'Unauthorized'], 401);
    }
}
