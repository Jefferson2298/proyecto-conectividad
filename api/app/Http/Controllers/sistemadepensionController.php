<?php

namespace App\Http\Controllers;

use App\sistemadepension;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class sistemadepensionController extends Controller
{
    public function inicio()
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

    public function tablaSistemaDePension()
    {
        $sistemapension = sistemadepension::all('Codigo', 'Nombre', 'Siglas','Vigencia');
        $count  = 1;
        foreach ($sistemapension  as $aux) {
            $aux->indice = $count;
            $count ++;
        }
        return $sistemapension;
    }

    public function mostrar($id)
    {
        return sistemadepension::find($id);
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
        $sistemapension = new sistemadepension();

        $sistemapension->Nombre = $request->get('Nombre');
        $sistemapension->Siglas = $request->get('Siglas');
        $sistemapension->Vigencia = 1;
        $sistemapension->save();

        return response()->json($sistemapension, 201);
    }

    public function actualizar(Request $request, $id)
    {
        $validacion = Validator::make($request->all(), [
            'Nombre' => 'required|max:100',
            'Siglas' => 'required|max:18'
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion, 400);
        }
        $sistemapension = sistemadepension::findOrFail($id);
        $sistemapension->Nombre = $request->get('Nombre');
        $sistemapension->Siglas = $request->get('Siglas');
        $sistemapension->save();
        return response()->json($sistemapension, 200);
    }

    public function eliminar($id)
    {
        $sistemapension = sistemadepension::findOrFail($id);
        $sistemapension->Vigencia = 0;
        $sistemapension->save();

        return response()->json($sistemapension, 200);
    }

    public function cambiarEstado($id)
    {
        $sistemadepension = sistemadepension::findOrFail($id);
        $sistemadepension->Vigencia = !$sistemadepension->Vigencia;
        $sistemadepension->save();

        return response()->json($sistemadepension, 200);
    }
}
