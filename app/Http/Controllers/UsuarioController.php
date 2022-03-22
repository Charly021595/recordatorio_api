<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\JwtAuth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function registrar(Request $request){
        $data = null;

        try {
            //Recoger post
            $json = $request->input('json', null);
            $params = json_decode($json);

            $email = (!is_null($json) && isset($params->email)) ? $params->email : null;
            $name = (!is_null($json) && isset($params->name)) ? $params->name : null; 
            $surname = (!is_null($json) && isset($params->surname)) ? $params->surname : null;
            $role = 'Usuario';
            $password = (!is_null($json) && isset($params->password)) ? $params->password : null;

            if (!is_null($email) && !is_null($password) && !is_null($name) && !is_null($surname)) {

                // Registrar usuario

                $usuario = new User();

                $pwd = Hash::make($password);

                $usuario->name = $name;
                $usuario->surname = $surname;
                $usuario->email = $email;
                $usuario->password = $pwd;
                $usuario->role = $role;

                //Comprobar usuario duplicado

                $isset_user = User::where('email', '=', $email)->get();

                if ($isset_user->count() == 0) {
                    //Guardar usuario

                    $usuario->save();

                    $data = array(
                        'status' => 'success',
                        'codigo' => 200,
                        'message' => 'Usuario registrado correctamente!!'
                    );
                }else{
                    $data = array(
                        'status' => 'usuario_duplicado',
                        'codigo' => 400,
                        'message' => 'El correo que tratas de utilizar ya esta tomado.'
                    );   
                }
            }else{
                $data = array(
                    'status' => 'error',
                    'codigo' => 400,
                    'message' => 'Usuario no Registrado'
                );
            }
        } catch (\Throwable $th) {
            $data = array(
                'status' => 'error',
                'codigo' => 400,
                'message' => $th->getMessage()
            );
        }
        return response()->json($data, 200);
    }

    public function login(Request $request){
        $data = null;
        try {
            $jwtAuth = new JwtAuth();

            //Recibir POST
            $json = $request->input('json', null);
            $params = json_decode($json);

            $email = (!is_null($json) && isset($params->email)) ? $params->email : null;
            $password = (!is_null($json) && isset($params->password)) ? $params->password : null;
            $getToken = (!is_null($json) && isset($params->gettoken)) ? $params->gettoken : null;

            if(!is_null($email) && !is_null($password) && ($getToken == null || $getToken == 'false')){
                $signup = $jwtAuth->signup($email, $password);
            }elseif ($getToken != null) {
                $signup = $jwtAuth->signup($email, $password, $getToken);
            }else{
                $signup = array(
                    'status' => 'error',
                    'message' => 'Falta Informacion'
                );
            }

            return response()->json($signup, 200);
            
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
