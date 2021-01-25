<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;

class AuthController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!$token = auth()->attempt($credentials)) {
            return $this->response->setCode(401)
                ->setMessage('Bilgileri kontrol edip tekrar deneyin!')->respond();
        }

        return $this->createNewToken($token);
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    public function logout()
    {
        auth()->logout();

        return $this->response->setCode(200)
            ->setMessage('İşlem başarılı.')->respond();
    }

    private function createNewToken($token)
    {
        $data = [
            'access_token' => $token,
        ];

        return $this->response->setCode(200)
            ->setMessage('İşlem başarılı.')->setData($data)->respond();
    }
}
