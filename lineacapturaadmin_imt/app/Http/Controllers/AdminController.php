<?php

namespace App\Http\Controllers;

use App\Models\Dependencia;
use App\Models\Tramite;
use App\Models\LineaCapturada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    // =====================================
    // DEPENDENCIAS
    // =====================================

    /**
     * Listado de dependencias.
     *
     * @return \Illuminate\View\View Vista con paginación de dependencias.
     */
    public function dependenciasIndex()
    {
        $dependencias = Dependencia::select('id','nombre','clave_dependencia','unidad_administrativa','created_at','updated_at')
            ->paginate(10);

        return view('dependencia', compact('dependencias'));
    }

    /**
     * Crea una nueva dependencia.
     *
     * @param Request $request Datos validados del formulario.
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito.
     */
    public function dependenciaStore(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required','string','max:255'],
            'clave_dependencia' => ['required','string','max:255'],
            'unidad_administrativa' => ['nullable','string','max:255'],
        ]);

        Dependencia::create($validated);
        Cache::forget('dependencias:list');

        return redirect()->route('dependencias.index')->with('success', 'Dependencia creada correctamente.');
    }

    /**
     * Actualiza una dependencia.
     *
     * @param Request $request Datos a actualizar.
     * @param Dependencia $dependencia Modelo a modificar.
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito.
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

        return redirect()->route('dependencias.index')->with('success', 'Dependencia actualizada correctamente.');
    }

    /**
     * Elimina una dependencia.
     *
     * @param Dependencia $dependencia Modelo a eliminar.
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito.
     */
    public function dependenciaDestroy(Dependencia $dependencia)
    {
        $dependencia->delete();
        Cache::forget('dependencias:list');

        return redirect()->route('dependencias.index')->with('success', 'Dependencia eliminada correctamente.');
    }

    // =====================================
    // TRÁMITES
    // =====================================

    /**
     * Listado de trámites.
     *
     * @return \Illuminate\View\View Vista con trámites ordenados.
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
        )
        ->orderBy('id', 'desc')
        ->get();

        return view('tramites', compact('tramites'));
    }

    /**
     * Crea un trámite.
     *
     * @param Request $request Datos del trámite.
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito.
     */
    public function tramitesStore(Request $request)
    {
        $validated = $request->validate([
            'clave_dependencia_siglas' => ['required','string','max:255'],
            'clave_tramite' => ['required','string','max:255'],
            'variante' => ['nullable','numeric'],
            'descripcion' => ['required','string'],
            'tramite_usoreservado' => ['nullable','string'],
            'fundamento_legal' => ['nullable','string'],
            'vigencia_tramite_de' => ['nullable','date'],
            'vigencia_tramite_al' => ['nullable','date'],
            'vigencia_lineacaptura' => ['nullable','numeric'],
            'tipo_vigencia' => ['nullable','string','max:5'],
            'clave_contable' => ['nullable','string','max:255'],
            'obligatorio' => ['nullable','in:S,N'],
            'agrupador' => ['nullable','string','max:255'],
            'tipo_agrupador' => ['nullable','string','max:5'],
            'clave_periodicidad' => ['nullable','string','max:5'],
            'clave_periodo' => ['nullable','string','max:10'],
            'nombre_monto' => ['nullable','string','max:255'],
            'variable' => ['nullable','in:S,N'],
            'cuota' => ['nullable','numeric'],
            'iva' => ['nullable','in:0,1'],
            'monto_iva' => ['nullable','numeric'],
            'actualizacion' => ['nullable','in:S,N'],
            'recargos' => ['nullable','in:S,N'],
            'multa_correccionfiscal' => ['nullable','in:S,N'],
            'compensacion' => ['nullable','in:S,N'],
            'saldo_favor' => ['nullable','in:S,N'],
        ]);

        Tramite::create($validated);
        Cache::forget('tramites:list');

        return redirect()->route('tramites')->with('success', 'Trámite creado correctamente.');
    }

    /**
     * Actualiza un trámite.
     *
     * @param Request $request Campos a actualizar.
     * @param Tramite $tramite Modelo a modificar.
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito.
     */
    public function tramitesUpdate(Request $request, Tramite $tramite)
    {
        $validated = $request->validate([
            'clave_dependencia_siglas' => ['nullable','string','max:255'],
            'clave_tramite' => ['nullable','string','max:255'],
            'variante' => ['nullable','numeric'],
            'descripcion' => ['nullable','string'],
            'tramite_usoreservado' => ['nullable','string'],
            'fundamento_legal' => ['nullable','string'],
            'vigencia_tramite_de' => ['nullable','date'],
            'vigencia_tramite_al' => ['nullable','date'],
            'vigencia_lineacaptura' => ['nullable','numeric'],
            'tipo_vigencia' => ['nullable','string','max:5'],
            'clave_contable' => ['nullable','string','max:255'],
            'obligatorio' => ['nullable','in:S,N'],
            'agrupador' => ['nullable','string','max:255'],
            'tipo_agrupador' => ['nullable','string','max:5'],
            'clave_periodicidad' => ['nullable','string','max:5'],
            'clave_periodo' => ['nullable','string','max:10'],
            'nombre_monto' => ['nullable','string','max:255'],
            'variable' => ['nullable','in:S,N'],
            'cuota' => ['nullable','numeric'],
            'iva' => ['nullable','in:0,1'],
            'monto_iva' => ['nullable','numeric'],
            'actualizacion' => ['nullable','in:S,N'],
            'recargos' => ['nullable','in:S,N'],
            'multa_correccionfiscal' => ['nullable','in:S,N'],
            'compensacion' => ['nullable','in:S,N'],
            'saldo_favor' => ['nullable','in:S,N'],
        ]);

        $tramite->update($validated);
        Cache::forget('tramites:list');

        return redirect()->route('tramites')->with('success', 'Trámite actualizado correctamente.');
    }

    /**
     * Devuelve datos de un trámite para edición (JSON).
     *
     * @param Tramite $tramite Modelo a consultar.
     * @return \Illuminate\Http\JsonResponse Campos habilitados para edición.
     */
    public function tramitesEdit(Tramite $tramite)
    {
        return response()->json($tramite->only([
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
        ]));
    }

    /**
     * Elimina un trámite.
     *
     * @param Tramite $tramite Modelo a eliminar.
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito.
     */
    public function tramitesDestroy(Tramite $tramite)
    {
        $tramite->delete();
        Cache::forget('tramites:list');

        return redirect()->route('tramites')->with('success', 'Trámite eliminado correctamente.');
    }

    // =====================================
    // LÍNEAS DE CAPTURA
    // =====================================

    /**
     * Listado de líneas de captura con filtros aplicados.
     *
     * @param Request $request Filtros y orden.
     * @return \Illuminate\View\View Vista con resultados y total.
     */
    public function lineasCapturadasIndex(Request $request)
    {
        $query = LineaCapturada::select(
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
            'detalle_tramites_snapshot',
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
        );

        // Aplicar filtros si existen en la solicitud
        if ($request->filled('tipo_persona')) {
            $query->where('tipo_persona', $request->tipo_persona);
        }

        if ($request->filled('estado_pago')) {
            $query->where('estado_pago', $request->estado_pago);
        }

        if ($request->filled('importe_min')) {
            $query->where('importe_total', '>=', $request->importe_min);
        }

        if ($request->filled('importe_max')) {
            $query->where('importe_total', '<=', $request->importe_max);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_solicitud', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_solicitud', '<=', $request->fecha_hasta);
        }

        // Búsqueda general
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('solicitud', 'like', "%{$search}%")
                  ->orWhere('rfc', 'like', "%{$search}%")
                  ->orWhere('curp', 'like', "%{$search}%")
                  ->orWhere('nombres', 'like', "%{$search}%")
                  ->orWhere('apellido_paterno', 'like', "%{$search}%")
                  ->orWhere('apellido_materno', 'like', "%{$search}%")
                  ->orWhere('razon_social', 'like', "%{$search}%");
            });
        }

        // Obtener el total de líneas antes de aplicar filtros
        $totalLineas = LineaCapturada::count();

        // Aplicar ordenamiento según el parámetro
        $orden = $request->get('orden', 'recientes');
        if ($orden === 'antiguas') {
            $lineas = $query->orderBy('id', 'asc')->get();
        } else {
            // Por defecto: más recientes (por fecha_solicitud desc y luego por ID desc)
            $lineas = $query->orderBy('fecha_solicitud', 'desc')
                           ->orderBy('id', 'desc')
                           ->get();
        }

        return view('lineas-captura', compact('lineas', 'totalLineas'));
    }

    /**
     * Elimina una línea de captura individual.
     *
     * @param LineaCapturada $linea Modelo a eliminar.
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito.
     */
    public function lineaCapturaDestroy(LineaCapturada $linea)
    {
        $linea->delete();

        return redirect()->route('lineas-captura')->with('success', 'Línea de captura eliminada correctamente.');
    }

    /**
     * Elimina líneas de captura por filtros múltiples.
     * Solo se ejecuta cuando hay filtros activos.
     *
     * @param Request $request Filtros a aplicar.
     * @return \Illuminate\Http\RedirectResponse Redirección con conteo o error.
     */
    public function lineasCapturaDeleteFiltered(Request $request)
    {
        $validated = $request->validate([
            'tipo_persona' => ['nullable', 'in:F,M'],
            'estado_pago' => ['nullable', 'string'],
            'importe_min' => ['nullable', 'numeric'],
            'importe_max' => ['nullable', 'numeric'],
            'fecha_desde' => ['nullable', 'date'],
            'fecha_hasta' => ['nullable', 'date'],
            'search' => ['nullable', 'string'],
        ]);

        $query = LineaCapturada::query();
        $hasFilters = false;

        // Filtro tipo persona
        if (!empty($validated['tipo_persona'])) {
            $query->where('tipo_persona', $validated['tipo_persona']);
            $hasFilters = true;
        }

        // Filtro estado pago
        if (!empty($validated['estado_pago'])) {
            $query->where('estado_pago', $validated['estado_pago']);
            $hasFilters = true;
        }

        // Filtro rango de importe
        if (!empty($validated['importe_min'])) {
            $query->where('importe_total', '>=', $validated['importe_min']);
            $hasFilters = true;
        }
        if (!empty($validated['importe_max'])) {
            $query->where('importe_total', '<=', $validated['importe_max']);
            $hasFilters = true;
        }

        // Filtro de fechas de vigencia
        if (!empty($validated['fecha_desde'])) {
            $query->whereDate('fecha_vigencia', '>=', $validated['fecha_desde']);
            $hasFilters = true;
        }
        if (!empty($validated['fecha_hasta'])) {
            $query->whereDate('fecha_vigencia', '<=', $validated['fecha_hasta']);
            $hasFilters = true;
        }

        // Filtro de búsqueda
        if (!empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function($q) use ($search) {
                $q->where('solicitud', 'like', "%{$search}%")
                  ->orWhere('rfc', 'like', "%{$search}%")
                  ->orWhere('curp', 'like', "%{$search}%")
                  ->orWhere('nombres', 'like', "%{$search}%")
                  ->orWhere('apellido_paterno', 'like', "%{$search}%")
                  ->orWhere('apellido_materno', 'like', "%{$search}%")
                  ->orWhere('razon_social', 'like', "%{$search}%");
            });
            $hasFilters = true;
        }

        // Solo eliminar si hay filtros activos
        if ($hasFilters) {
            $count = $query->count();
            $query->delete();

            return redirect()->route('lineas-captura')->with('success', "Se eliminaron {$count} líneas de captura filtradas correctamente.");
        }

        return redirect()->route('lineas-captura')->with('error', 'Debe aplicar al menos un filtro para eliminar registros filtrados.');
    }
}