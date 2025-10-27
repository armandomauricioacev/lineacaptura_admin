<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineaCapturada extends Model
{
    use HasFactory;

    protected $table = 'lineas_capturadas';

    protected $fillable = [
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
        'procesado_exitosamente',
    ];

     // Agregar cast para JSON
    protected $casts = [
        'detalle_tramites_snapshot' => 'array', // ← NUEVA LÍNEA AGREGADA
    ];
}