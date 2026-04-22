<?php

namespace App\Controllers;

use App\Helpers\Core\AppSettings;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\Key;
use stdClass;

class AuthController extends BaseController
{
//! Next, go to the file passwordTrait to use its methods for user login and password security. In a model you can do 'use PasswordTrait' and then access its methods. **The use statement must be INSIDE the class (AccountModel class for example)
    public function __construct(private AppSettings $appSettings)
    {
        parent::__construct();
    }

    public function handleGenerateToken(Request $request, Response $response, array $args): Response
    {
        // $user = $request->getParsedBody();
        // $email = $user['email'];
        // $email = "marko@gmail.com";
        $issuedAt = time();
        $expire = $issuedAt + 60;

        //TODO build add a pay load to claims(user_info)
        $secret = $this->appSettings->get('jwt')['secret'];

        //? Check ou the firebase/php-jwt's docs on github
        //* 1. create an associative array with the registered and private claims (user info. user_id/email)=> to be used for the payload section
        //* 2. USE the JWT::encode() to encode token

        $key = 'key-for-my-camera-api-that-is-used-to-add-security-to-the-application';

        $payload = [
            "sub" => $issuedAt + 6,
            'aud' => 'example.com',
            'iss' => 'example.org',
            "iat" => $issuedAt,
            "exp" => $expire,
        ];

        $encoded_jwt = JWT::encode($payload, $key, 'HS256');
        $headers = new stdClass();
        // $encoded_jwt = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwibWFya2V0Ijp0cnVlfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c";
        $decoded = JWT::decode($encoded_jwt, new Key($key, 'HS256'), $headers);

        dd($decoded);

        $jwt_data = [
            "status" => "success",
            "token" => $encoded_jwt
        ];

        return $this->renderJson($response, $jwt_data, 200);
    }

    public function handleUserLogin(Request $request, Response $response, array $args): Response
    {
        //* First, verify user credentials username and psw: generate token if valid, return invalid json response if invalid or throw HTTP specialized exception.

        //* If valid, include the account info in the JWT's payload section: role, user_id, email (don't put first/last name or password, even email is not required)

        $issuedAt = time();
        $expire = $issuedAt + 60;

        //TODO build add a pay load to claims(user_info)
        $secret = $this->appSettings->get('jwt')['secret'];

        //? Check ou the firebase/php-jwt's docs on github
        //* 1. create an associative array with the registered and private claims (user info. user_id/email)=> to be used for the payload section
        //* 2. USE the JWT::encode() to encode token

        $key = 'key-for-my-camera-api-that-is-used-to-add-security-to-the-application';

        $payload = [
            "sub" => $issuedAt + 6,
            // "email" => $email,
            "iat" => $issuedAt,
            "exp" => $expire,
        ];

        // $encoded_jwt = JWT::encode($payload, $key, 'HS256'); //! Use this
        $headers = new stdClass();
        $encoded_jwt = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwibWFya2V0Ijp0cnVlfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c";
        $decoded = JWT::decode($encoded_jwt, new Key($key, 'HS256'), $headers);

        dd($encoded_jwt);

        $jwt_data = [
            "status" => "success",
            "token" => $encoded_jwt
        ];

        return $this->renderJson($response, $jwt_data, 200);
    }
}
