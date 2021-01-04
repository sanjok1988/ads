<?php

namespace Sanjok\Blog\Api;

use Exception;
use Validator;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Sanjok\Blog\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Sanjok\Blog\Api\BaseApiController;
use Illuminate\Validation\ValidationException;

class PassportAuthController extends BaseApiController
{
    public $successStatus = 200;

    public function login(Request $request)
    {

        try {
            $request->validate([
                'email' => ['required'],
                'password' => ['required']
            ]);

            if (Auth::attempt($request->only('email', 'password'))) {
                return response()->json(Auth::user(), 200);
            }

            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
            // $token = $user->createToken('accessToken')->plainTextToken;

            // return response(
            //     [
            //         'status' => 200,
            //         'token' => $token,
            //         'token_type' => 'Bearer'
            //     ],
            //     Response::HTTP_OK
            // );
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
            ]);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, Response::HTTP_OK);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $input = $request->all();
        $password = $request->password;

        DB::transaction(function () use ($input) {
            $input['password'] = bcrypt($input['password']);

            $user = User::create($input);
            $oClient = OClient::where('password_client', 1)->first();

            return $this->getTokenAndRefreshToken($oClient, $user->email, $input['password']);
        });
    }

    public function getTokenAndRefreshToken(OClient $oClient, $email, $password)
    {
        $oClient = OClient::where('password_client', 1)->first();

        $http = new Client;
        $response = $http->request('POST', 'http://passport.dev/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $email,
                'password' => $password,
                'scope' => '*',
            ],
        ]);

        $result = json_decode((string) $response->getBody(), true);
        return response()->json($result, $this->successStatus);
    }
}
