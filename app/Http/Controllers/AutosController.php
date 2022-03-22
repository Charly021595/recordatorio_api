<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Models\Auto;

class AutosController extends Controller
{
    public function index(Request $request){
        try {
            $hash = $request->header('Authorization', null);

            $jwtAuth = new JwtAuth();
            $checkToken = $jwtAuth->checkToken($hash);

            if ($checkToken) {
                echo 'autenticado';
            }else{
                echo 'no autenticado';
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function registrar_auto(Request $request){
        try {
            $hash = $request->header('Authorization', null);

            $jwtAuth = new JwtAuth();
            $checkToken = $jwtAuth->checkToken($hash);

            if ($checkToken) {
                //Recoger Datos por post
                $json = $request->input('json', null);
                $params = json_decode($json);

                //Obtener usuario autenticado
                $usuario = $jwtAuth->checkToken($hash, true);

                if (isset($params->titulo) && isset($params->descripcion) && isset($params->orecio) && isset($params->estatus)) {
                    //Registrar Auto
                    $auto = new Auto();
                    $auto->titulo = $params->titulo;
                    $auto->descripcion = $params->descripcion;
                    $auto->precio = $params->precio;
                    $auto->status = $params->estatus;

                    $auto->save();
                }else{
                    throw new Exception("La informacion esta incompleta");
                }

            }else{
                
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
