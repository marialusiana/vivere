<?php
/**
 * Created by PhpStorm.
 * User: oselwang
 * Date: 27/03/19
 * Time: 16.03
 */

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\OauthClient;
use App\Traits\apiJsonReturnTrait;
use Artisan;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sentinel;

class AuthController extends Controller
{
    use apiJsonReturnTrait;
   
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Client
     */
    private $client;
    /**
     * @var OauthClient
     */
    private $oauthClient;

    /**
     * AuthController constructor.
     * @param Request $request
     * @param Client $client
     * @param OauthClient $oauthClient
     */
    public function __construct(Request $request, Client $client, OauthClient $oauthClient)
    {
        $this->request = $request;
        $this->client = $client;
        $this->oauthClient = $oauthClient;
    }

    public function login()
    {
        $credentials = [
            'email' => $this->request->email,
            'password' => $this->request->password
        ];

        if ($user = \Sentinel::authenticate($credentials)) {

            $token = $this->getAccessToken($user);
            return $this->returnJson(['access_token' => $token, 'topic' => $user->topic], '', 200);
        } else {
            return $this->returnJson([], "Credentials do not match", 400, 'error');
        }
    }

    public function logout()
    {
        \Auth::guard('api')->user()->token()->revoke();
        Artisan::call('cache:clear');
        return $this->returnJson([], 'Logout successfully', 200, 'ok');
    }

    public function register(RegisterRequest $registerRequest)
    {
        $user = \DB::transaction(function () use ($registerRequest) {
            return $registerRequest->handle();
        });

        $token = $this->getAccessToken($user);
        return $this->returnJson(['access_token' => $token, 'topic' => $user->topic], '', 200);
    }

  
    public function getAccessToken($user)
    {
        $token = $user->createToken('nApp')->accessToken;
        return $token;
    }
}