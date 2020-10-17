<?php

namespace App\Http\Controllers;

use App\SistemaPension;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SistemaPensionController extends Controller
{
    public function listar()
    {
        $sistemapension = DB::table('sistemapensiones as sp')
            ->select('sp.Codigo', 'sp.Nombre', 'sp.Siglas','sp.Vigencia')
            ->get();
            $count  = 1;
            foreach ($sistemapension  as $aux) {
                $aux->indice = $count;
                $count ++;
            }
        return response()->json($sistemapension, 200);
    }

    public function leer($id)
    {
        return SistemaPension::find($id);
    }

    public function registrar(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'Nombre' => 'required|max:100',
            'Siglas' => 'required|max:18',
        ], [
            'required' => ':attribute es obligatorio',
            'max' => ':attribute llego al limite de letras'
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion->errors()->first(), 400);
        }
        $sistemapension = new SistemaPension();

        $sistemapension->Nombre = $request->get('Nombre');
        $sistemapension->Siglas = $request->get('Siglas');
        $sistemapension->Vigencia = 1;
        $sistemapension->save();

        return response()->json($sistemapension, 201);
    }

    ///public function actualizar(Request $request, $id)
    public function actualizar(Request $request, $id)
    {
        $validacion = Validator::make($request->all(), [
            'Nombre' => 'required|max:100',
            'Siglas' => 'required|max:18'
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion, 400);
        }
        $sistemapension = SistemaPension::findOrFail($id);
        $sistemapension->Nombre = $request->get('Nombre');
        $sistemapension->Siglas = $request->get('Siglas');
        $sistemapension->update();
        return response()->json($sistemapension, 200);
    }

    public function eliminar($id)
    {
        $sistemapension = SistemaPension::findOrFail($id);
        $sistemapension->Vigencia = 0;
        $sistemapension->save();

        return response()->json($sistemapension, 200);
    }

    public function cambiarEstado($id)
    {
        $sistemadepension = SistemaPension::findOrFail($id);
        $sistemadepension->Vigencia = !$sistemadepension->Vigencia;
        $sistemadepension->save();

        return response()->json($sistemadepension, 200);
    }
}
