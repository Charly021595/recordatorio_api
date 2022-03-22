<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class JwtAuth{
    public $key;

    public function __construct(){
        $this->key = 'esta-es-mi-clave-secreta-758458934753987493';
    }

    public function signup($email, $password, $getToken = null){
        try {
            $usuario = User::where(
                array(
                    'email' => $email
                ))->first();

            if(Hash::check($password, $usuario->password)){
                $signup = false;

                if (is_object($usuario)) {
                    $signup = true;
                }

                if ($signup) {
                    // Generar
                    $token = array(
                        'sub' => $usuario->id,
                        'email' => $usuario->email,
                        'name' => $usuario->name,
                        'surname' => $usuario->surname,
                        'iat' => time(),
                        'exp' => time() + (7 * 24 * 60 * 60)
                    );

                    $jwt = JWT::encode($token, $this->key); 
                    $decoded  = JWT::decode($jwt, $this->key, array('HS256'));

                    if (is_null($getToken)) {
                        return $jwt;
                    }else{
                        return $decoded;
                    }
                }else{
                    // Devolver un error
                    return array('status' => 'error', 'message' => "Login ha fallado !!");
                }
            }else{
                return "El Usuario o Contraseña no coinciden";
            }
        } catch (\Throwable $th) {
            return array('status' => 'error', 'message' => $th->getMessage());
        }
    }


    public function checkToken($jwt, $getIdentity = false){
        $auth = false;

        try{
            $decoded = JWT::decode($jwt, $this->key, array('HS256'));
        }catch(\UnexpectedValueException $e){
            $auth = false;
        }catch(\DomainException $e){
            $auth = false;
        }

        // if (isset($decoded) && is_object($decoded) && isset($decoded->sub)) {
        //     $auth = true;
        // }else{
        //     $auth = false;
        // }

        if (isset($decoded) && is_object($decoded) && isset($decoded->sub)) {
            $auth = true;
        }else{
            $auth = false;
        }

        if ($getIdentity) {
            return $decoded;
        }

        return $auth;
    }

}