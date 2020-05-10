<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Validator;
use function GuzzleHttp\json_decode;

class UserController extends Controller
{
public $successStatus = 200;
/**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){
        $email = $request->email;
        $password = $request->password;
        if(Auth::attempt(['email' => $email, 'password' => $password])){
            $user = Auth::user();
            $request->request->add([
                'username' => $email,
                'password' => $password,
                'grant_type' => 'password',
                'client_id' => env('CLIENT_ID'),
                'client_secret' => env('CLIENT_SECRET'),
                'scope' => '*'
            ]);

            $tokenRequest = Request::create(
                env('APP_URL').'/oauth/token',
                'post'
            );
            $response = Route::dispatch($tokenRequest);

            $success['token'] =  json_decode($response->getContent());
            $success['user'] =  $user->name;
            // $success['other'] =  json_decode($response->getContent());

            return response()->json(['success' => $success], $this-> successStatus);
        }
        else{
            return response()->json(['error'=>'Email or Password is incorrect'], 401);
        }
    }
/**
     * login with mobile api
     *
     * @return \Illuminate\Http\Response
     */
    public function mobileLogin(){
        $mobile = request('mobile');
        if(User::where('mobile' , $mobile)->exists()){
            // $user = Auth::user();
            // $success['token'] =  $user->createToken('MyApp')-> accessToken;
            $otp = rand(1000,9999);
            $user = User::where("mobile", $mobile);
            $user->update(["otp" => $otp]);

            return response()->json(['mobile' => $mobile, 'otp' => $otp], $this-> successStatus);
        }
        else{
            return response()->json(['error'=>'Mobile number is not found'], 401);
        }
    }
    /**
     * login with mobile otp validate api
     *
     * @return \Illuminate\Http\Response
     */
    public function otpVerification(Request $request){
        $check = User::where([['mobile', "=", request('mobile')], ['otp', "=", request('otp')]])->first();
        if($check){
            Auth::login($check);
            $user = Auth::user();

            $request->request->add([
                'username' => $user->email,
                'password' => $user->password,
                'grant_type' => 'password',
                'client_id' => env('CLIENT_ID'),
                'client_secret' => env('CLIENT_SECRET'),
                'scope' => '*'
            ]);

            $tokenRequest = Request::create(
                env('APP_URL').'/oauth/token',
                'post'
            );

            $response = Route::dispatch($tokenRequest);
            $success['token'] =  json_decode($response->getContent());
            // $success['token'] =  $user->createToken('MyApp')->accessToken;
            $success['user'] =  $user->name;
            return response()->json(['success' => $success], $this-> successStatus);
        }
        else{
            return response()->json(['error'=>'Invalid OTP'], 401);
        }
    }
/**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
                    return response()->json(['error'=>$validator->errors()], 401);
                }
        $input = $request->all();
                $input['password'] = bcrypt($input['password']);
                $user = User::create($input);
                $success['token'] =  $user->createToken('MyApp')-> accessToken;
                $success['name'] =  $user->name;
        return response()->json(['success'=>$success], $this-> successStatus);
    }
/**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this-> successStatus);
    }
}
