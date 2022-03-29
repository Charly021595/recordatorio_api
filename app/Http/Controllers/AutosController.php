<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Models\Auto;
use Illuminate\Support\Facades\DB;

class AutosController extends Controller
{
    public function index(Request $request){
        try {
            $hash = $request->header('Authorization', null);

            $jwtAuth = new JwtAuth();
            $checkToken = $jwtAuth->checkToken($hash);

            if ($checkToken) {
                $autos = Auto::all();
                $data = array(
                    'auto' => $autos,
                    'status' => 'success',
                    'code' => 200
                );
            }else{
                $data = array(
                    'mensaje' => 'fallo el login',
                    'status' => 'error',
                    'code' => 300
                );
            }
        } catch (\Throwable $th) {
            $var_mensaje = $th->getMessage();
            $data = array(
                'mensaje' => $var_mensaje,
                'status' => 'error',
                'code' => 400
            );
        }
        return $data;
    }

    public function filtros_busqueda(Request $request){
        try {
            $hash = $request->header('Authorization', null);

            $jwtAuth = new JwtAuth();
            $checkToken = $jwtAuth->checkToken($hash);

            if ($checkToken) {
                //Recoger Datos por post
                $json = $request->input('json', null);
                $params = json_decode($json);
                $params_array = json_decode($json, true);

                //Obtener usuario autenticado
                $usuario = $jwtAuth->checkToken($hash, true);

                $titulo = (!is_null($json) && isset($params->titulo)) ? $params->titulo : null;
                $descripcion = (!is_null($json) && isset($params->descripcion)) ? $params->descripcion : null;
                $precio = (!is_null($json) && isset($params->precio)) ? $params->precio : null;
                $status = (!is_null($json) && isset($params->status)) ? $params->status : null;

                $auto = Auto::select('*');

                if (isset($params->titulo) && $params->titulo != null && $params->titulo != '') {
                    $auto->Where('titulo', 'Like', "%$params->titulo%");
                }

                if (isset($params->descripcion) && $params->descripcion != null && $params->descripcion != '') {
                    $auto->Where('descripcion', 'Like', "%$params->descripcion%");
                }

                if (isset($params->precio) && $params->precio != null && $params->precio != '') {
                    $auto->Where('precio', '=', $params->precio);
                }

                if (isset($params->status) && $params->status != null && $params->status != '') {
                    $auto->Where('status', '=', $params->status);
                }
                
                $resultados = $auto->orderBy('id', 'asc')->get();

                $data = array(
                    'auto' => $resultados,
                    'status' => 'success',
                    'code' => 200
                );
            }else{
                $data = array(
                    'mensaje' => 'fallo el login',
                    'status' => 'error',
                    'code' => 300
                );
            }
        } catch (\Throwable $th) {
            $var_mensaje = $th->getMessage();
            $data = array(
                'mensaje' => $var_mensaje,
                'status' => 'error',
                'code' => 400
            );
        }
        return $data;
    }

    public function registrar_auto(Request $request){
        try {
            DB::beginTransaction();
            $hash = $request->header('Authorization', null);

            $jwtAuth = new JwtAuth();
            $checkToken = $jwtAuth->checkToken($hash);

            if ($checkToken) {
                //Recoger Datos por post
                $json = $request->input('json', null);
                $params = json_decode($json);
                $params_array = json_decode($json, true);

                //Obtener usuario autenticado
                $usuario = $jwtAuth->checkToken($hash, true);

                //validacion
                $request->merge($params_array);
                $validate = \Validator::make($params_array, [
                    'titulo' => 'required|min:5',
                    'descripcion' => 'required',
                    'precio' => 'required',
                    'status' => 'required'
                ]);

                if ($validate->fails()) {
                    return response()->json($validate->errors(), 400);
                }
                
                //Registrar Auto
                $auto = new Auto();
                $auto->usuario_id = $usuario->sub;
                $auto->titulo = $params->titulo;
                $auto->descripcion = $params->descripcion;
                $auto->precio = $params->precio;
                $auto->status = $params->status;

                $auto->save();
                DB::commit();

                $data = array(
                    'auto' => $auto,
                    'status' => 'success',
                    'code' => 200
                );
                
            }else{
                // Devolver error
                $data = array(
                    'message' => 'Login incorrecto',
                    'status' => 'error',
                    'code' => 300
                );
            }
        } catch (\Throwable $th) {
            $var_mensaje = $th->getMessage();
            // Devolver error
            $data = array(
                'message' => "$var_mensaje",
                'status' => 'error',
                'code' => 400
            );
            DB::rollback();
        }
        return $data;
    }

    public function editar_auto(Request $request){
        try {
            DB::beginTransaction();
            $hash = $request->header('Authorization', null);

            $jwtAuth = new JwtAuth();
            $checkToken = $jwtAuth->checkToken($hash);

            if ($checkToken) {
                //Recoger Datos por post
                $json = $request->input('json', null);
                $params = json_decode($json);
                $params_array = json_decode($json, true);

                //Obtener usuario autenticado
                $usuario = $jwtAuth->checkToken($hash, true);

                //validacion
                $request->merge($params_array);
                $validate = \Validator::make($params_array, [
                    'id' => 'required|min:5',
                    'titulo' => 'required|min:5',
                    'descripcion' => 'required',
                    'precio' => 'required',
                    'status' => 'required'
                ]);



                if ($validate->fails()) {
                    return response()->json($validate->errors(), 400);
                }
                
                //Registrar Auto
                $auto = Auto::findOrFail($params->id);
                $auto->usuario_id = $usuario->sub;
                $auto->titulo = $params->titulo;
                $auto->descripcion = $params->descripcion;
                $auto->precio = $params->precio;
                $auto->status = $params->status;

                $auto->update();
                DB::commit();

                $data = array(
                    'mensaje' => "Auto Actualizado",
                    'status' => 'success',
                    'code' => 200
                );
                
            }else{
                // Devolver error
                $data = array(
                    'message' => 'Login incorrecto',
                    'status' => 'error',
                    'code' => 300
                );
            }
        } catch (\Throwable $th) {
            $var_mensaje = $th->getMessage();
            // Devolver error
            $data = array(
                'message' => "$var_mensaje",
                'status' => 'error',
                'code' => 400
            );
            DB::rollback();
        }
        return $data;
    }
}
