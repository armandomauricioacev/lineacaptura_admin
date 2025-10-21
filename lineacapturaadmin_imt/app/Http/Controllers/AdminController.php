<?php

namespace App\Http\Controllers;

use App\Models\Dependencia;
use App\Models\Tramite;
use App\Models\LineaCapturada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    /**
     * Listado de dependencias.
     */
    public function dependenciasIndex()
    {
        $dependencias = Dependencia::select('id','nombre','clave_dependencia','unidad_administrativa','created_at','updated_at')
            ->paginate(10);

        return view('dependencia', compact('dependencias'));
    }

    /**
     * Actualiza una dependencia.
     */
    public function dependenciaUpdate(Request $request, Dependencia $dependencia)
    {
        $validated = $request->validate([
            'nombre' => ['required','string','max:255'],
            'clave_dependencia' => ['required','string','max:255'],
            'unidad_administrativa' => ['nullable','string','max:255'],
        ]);

        $dependencia->update($validated);
        Cache::forget('dependencias:list');

        return redirect()->route('dependencias.index')->with('status', __('Dependencia actualizada correctamente.'));
    }

    /**
     * Elimina una dependencia.
     */
    public function dependenciaDestroy(Dependencia $dependencia)
    {
        $dependencia->delete();
        Cache::forget('dependencias:list');

        return redirect()->route('dependencias.index')->with('status', __('Dependencia eliminada correctamente.'));
    }

    /**
     * Listado de trámites.
     */
    public function tramitesIndex()
    {
        $tramites = Tramite::select(
            'id',
            'clave_dependencia_siglas',
            'clave_tramite',
            'variante',
            'descripcion',
            'tramite_usoreservado',
            'fundamento_legal',
            'vigencia_tramite_de',
            'vigencia_tramite_al',
            'vigencia_lineacaptura',
            'tipo_vigencia',
            'clave_contable',
            'obligatorio',
            'agrupador',
            'tipo_agrupador',
            'clave_periodicidad',
            'clave_periodo',
            'nombre_monto',
            'variable',
            'cuota',
            'iva',
            'monto_iva',
            'actualizacion',
            'recargos',
            'multa_correccionfiscal',
            'compensacion',
            'saldo_favor',
            'created_at',
            'updated_at'
        )->paginate(15);

        return view('tramites', compact('tramites'));
    }

    /**
     * Actualiza un trámite.
     */
    public function tramitesUpdate(Request $request, Tramite $tramite)
    {
        $validated = $request->validate([
            'clave_dependencia_siglas' => ['nullable','string','max:255'],
            'clave_tramite' => ['nullable','string','max:255'],
            'variante' => ['nullable','string','max:255'],
            'descripcion' => ['nullable','string'],
            'tramite_usoreservado' => ['nullable'],
            'fundamento_legal' => ['nullable','string'],
            'vigencia_tramite_de' => ['nullable','string','max:255'],
            'vigencia_tramite_al' => ['nullable','string','max:255'],
            'vigencia_lineacaptura' => ['nullable','string','max:255'],
            'tipo_vigencia' => ['nullable','string','max:255'],
            'clave_contable' => ['nullable','string','max:255'],
            'obligatorio' => ['nullable'],
            'agrupador' => ['nullable','string','max:255'],
            'tipo_agrupador' => ['nullable','string','max:255'],
            'clave_periodicidad' => ['nullable','string','max:255'],
            'clave_periodo' => ['nullable','string','max:255'],
            'nombre_monto' => ['nullable','string','max:255'],
            'variable' => ['nullable','string','max:255'],
            'cuota' => ['nullable'],
            'iva' => ['nullable'],
            'monto_iva' => ['nullable'],
            'actualizacion' => ['nullable'],
            'recargos' => ['nullable'],
            'multa_correccionfiscal' => ['nullable'],
            'compensacion' => ['nullable'],
            'saldo_favor' => ['nullable'],
        ]);

        $tramite->update($validated);
        Cache::forget('tramites:list');

        return redirect()->route('tramites')->with('status', __('Trámite actualizado correctamente.'));
    }

    /**
     * Elimina un trámite.
     */
    public function tramitesDestroy(Tramite $tramite)
    {
        $tramite->delete();
        Cache::forget('tramites:list');

        return redirect()->route('tramites')->with('status', __('Trámite eliminado correctamente.'));
    }

    /**
     * Listado de líneas de captura.
     */
    public function lineasCapturadasIndex()
    {
        $lineas = LineaCapturada::select(
            'id',
            'tipo_persona',
            'curp',
            'rfc',
            'razon_social',
            'nombres',
            'apellido_paterno',
            'apellido_materno',
            'dependencia_id',
            'tramite_id',
            'solicitud',
            'importe_cuota',
            'importe_iva',
            'importe_total',
            'json_generado',
            'estado_pago',
            'fecha_solicitud',
            'fecha_vigencia',
            'created_at',
            'updated_at',
            'json_recibido',
            'id_documento',
            'tipo_pago',
            'html_codificado',
            'resultado',
            'linea_captura',
            'importe_sat',
            'fecha_vigencia_sat',
            'errores_sat',
            'fecha_respuesta_sat',
            'procesado_exitosamente'
        )
        ->orderBy('id')
        ->get();

        return view('lineas-captura', compact('lineas'));
    }
}