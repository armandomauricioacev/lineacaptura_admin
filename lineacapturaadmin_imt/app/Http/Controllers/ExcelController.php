<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Models\Dependencia;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExcelController extends Controller
{
    /**
     * Procesa archivo Excel de trámites.
     *
     * @param Request $request Archivo Excel recibido por formulario.
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito o error.
     */
    public function uploadTramites(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:10240', // Máximo 10MB
        ]);

        try {
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            
            // Buscar la hoja "Layout" específicamente
            $worksheet = null;
            foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
                if ($sheet->getTitle() === 'Layout') {
                    $worksheet = $sheet;
                    break;
                }
            }
            
            // Si no se encuentra la hoja Layout, usar la primera hoja
            if (!$worksheet) {
                $worksheet = $spreadsheet->getActiveSheet();
            }
            
            $rows = $worksheet->toArray();

            // Remover la primera fila (encabezados)
            array_shift($rows);

            // Filtrar filas completamente vacías
            $rows = array_filter($rows, function($row) {
                return !empty(array_filter($row, function($cell) {
                    return !empty(trim($cell ?? ''));
                }));
            });

            // Reindexar el array después del filtrado para que los índices sean consecutivos
            $rows = array_values($rows);

            // Validar y procesar datos
            $tramitesData = [];
            $errores = [];

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 porque removimos encabezados y empezamos en 1

                // Debug: Mostrar los primeros valores de la fila para diagnóstico
                Log::info("Procesando fila {$rowNumber}: " . json_encode(array_slice($row, 0, 10)));

                // Mapear columnas del Excel según el orden correcto (A-V)
                $tramiteData = [
                    'clave_dependencia_siglas' => trim($row[0] ?? ''), // A
                    'clave_tramite' => trim($row[1] ?? ''), // B
                    'variante' => trim($row[2] ?? ''), // C
                    'descripcion' => trim($row[3] ?? ''), // D
                    'tramite_usoreservado' => trim($row[4] ?? ''), // E
                    'fundamento_legal' => trim($row[5] ?? ''), // F
                    'vigencia_tramite_de' => $this->parseDate($row[6] ?? ''), // G
                    'vigencia_tramite_al' => $this->parseDate($row[7] ?? ''), // H
                    'vigencia_lineacaptura' => $this->parseNumeric($row[8] ?? ''), // I
                    'tipo_vigencia' => trim($row[9] ?? ''), // J
                    'clave_contable' => trim($row[10] ?? ''), // K
                    'obligatorio' => trim($row[11] ?? ''), // L
                    'agrupador' => trim($row[12] ?? ''), // M
                    'tipo_agrupador' => trim($row[13] ?? ''), // N
                    'nombre_monto' => trim($row[14] ?? ''), // O
                    'variable' => trim($row[15] ?? ''), // P
                    'cuota' => $this->parseNumeric($row[16] ?? ''), // Q
                    'actualizacion' => trim($row[17] ?? ''), // R
                    'recargos' => trim($row[18] ?? ''), // S
                    'multa_correccionfiscal' => trim($row[19] ?? ''), // T
                    'compensacion' => trim($row[20] ?? ''), // U
                    'saldo_favor' => trim($row[21] ?? ''), // V
                ];

                // Debug: Mostrar los valores parseados
                Log::info("Datos parseados fila {$rowNumber}: vigencia_tramite_de=" . ($tramiteData['vigencia_tramite_de'] ?? 'NULL') . 
                          ", vigencia_lineacaptura=" . ($tramiteData['vigencia_lineacaptura'] ?? 'NULL') . 
                          ", cuota=" . ($tramiteData['cuota'] ?? 'NULL') .
                          ", raw_vigencia_de=" . ($row[6] ?? 'NULL') . 
                          ", raw_vigencia_lc=" . ($row[8] ?? 'NULL') . 
                          ", raw_cuota=" . ($row[16] ?? 'NULL'));

                // Agregar campos fijos según reglas de negocio
                $tramiteData['clave_periodicidad'] = 'N';
                $tramiteData['clave_periodo'] = '099';
                $tramiteData['monto_iva'] = 0.00;

                // Lógica del IVA basada en tipo_agrupador
                $tipoAgrupador = strtoupper(trim($tramiteData['tipo_agrupador'] ?? ''));
                if ($tipoAgrupador === 'P') {
                    $tramiteData['iva'] = 1;
                } elseif ($tipoAgrupador === 'S') {
                    $tramiteData['iva'] = 0;
                } else {
                    $tramiteData['iva'] = 0; // Por defecto
                }

                // Debug logging para valores específicos
                Log::info("Fila $rowNumber - Valores raw:", [
                    'vigencia_tramite_de_raw' => $row[6] ?? 'NULL',
                    'vigencia_tramite_al_raw' => $row[7] ?? 'NULL',
                    'vigencia_lineacaptura_raw' => $row[8] ?? 'NULL',
                    'cuota_raw' => $row[16] ?? 'NULL',
                    'tipo_agrupador_raw' => $row[13] ?? 'NULL',
                ]);

                Log::info("Fila $rowNumber - Valores parseados:", [
                    'vigencia_tramite_de' => $tramiteData['vigencia_tramite_de'],
                    'vigencia_tramite_al' => $tramiteData['vigencia_tramite_al'],
                    'vigencia_lineacaptura' => $tramiteData['vigencia_lineacaptura'],
                    'cuota' => $tramiteData['cuota'],
                    'tipo_agrupador' => $tramiteData['tipo_agrupador'],
                    'iva' => $tramiteData['iva'],
                ]);

                // Validar datos requeridos
                $erroresRow = $this->validarTramite($tramiteData, $rowNumber);
                if (!empty($erroresRow)) {
                    $errores = array_merge($errores, $erroresRow);
                    continue;
                }

                $tramitesData[] = $tramiteData;
            }

            // Si hay errores, no procesar nada
            if (!empty($errores)) {
                $errorMessage = "Se encontraron errores en el archivo Excel:\n" . implode("\n", $errores);
                return back()->withErrors(['excel_error' => $errorMessage]);
            }

            // ELIMINAR TODOS LOS TRÁMITES EXISTENTES ANTES DE CARGAR LOS NUEVOS
            // Usar DELETE en lugar de TRUNCATE debido a restricciones de clave foránea
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Tramite::query()->delete();
            DB::statement('ALTER TABLE tramites AUTO_INCREMENT = 1;');
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Insertar los nuevos trámites dentro de una transacción
            DB::transaction(function () use ($tramitesData) {
                foreach ($tramitesData as $tramiteData) {
                    Tramite::create($tramiteData);
                }
            });

            return back()->with('success', 'Excel procesado con éxito. Se procesaron ' . count($tramitesData) . ' trámites.');

        } catch (\Exception $e) {
            Log::error('Error al procesar Excel de trámites: ' . $e->getMessage());
            return back()->withErrors(['excel_error' => 'Error al procesar el archivo Excel: ' . $e->getMessage()]);
        }
    }

    /**
     * Valida datos de un trámite extraídos del Excel.
     *
     * @param array $data Datos normalizados del trámite.
     * @param int $rowNumber Número de fila en el Excel.
     * @return array Lista de mensajes de error; vacío si todo es válido.
     */
    private function validarTramite($data, $rowNumber)
    {
        $errores = [];

        // Validar campos requeridos
        if (empty($data['clave_tramite'])) {
            $errores[] = "Fila $rowNumber: Clave trámite es requerida";
        }

        if (empty($data['descripcion'])) {
            $errores[] = "Fila $rowNumber: Descripción es requerida";
        }

        if (empty($data['vigencia_tramite_de'])) {
            $errores[] = "Fila $rowNumber: Vigencia del trámite (De) es requerida";
        }

        if (empty($data['vigencia_tramite_al'])) {
            $errores[] = "Fila $rowNumber: Vigencia del trámite (Al) es requerida";
        }

        if ($data['vigencia_lineacaptura'] === null || $data['vigencia_lineacaptura'] === '') {
            $errores[] = "Fila $rowNumber: Vigencia línea captura es requerida";
        }

        if ($data['cuota'] === null || $data['cuota'] === '') {
            $errores[] = "Fila $rowNumber: Cuota es requerida";
        }

        return $errores;
    }

    /**
     * Convierte valor Excel a fecha 'Y-m-d'.
     *
     * @param mixed $value Valor crudo de celda.
     * @return string|null Fecha formateada o null si no válida.
     */
    private function parseDate($value)
    {
        if ($value === null || $value === '' || trim($value) === '') {
            return null;
        }

        // Limpiar el valor
        $cleanValue = trim($value);

        // Si es un número (fecha de Excel)
        if (is_numeric($cleanValue)) {
            try {
                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($cleanValue);
                return $date->format('Y-m-d');
            } catch (\Exception $e) {
                // Si falla, intentar como timestamp
                $timestamp = (int)$cleanValue;
                if ($timestamp > 0) {
                    return date('Y-m-d', $timestamp);
                }
            }
        }

        // Si es una cadena, intentar parsearla
        try {
            $timestamp = strtotime($cleanValue);
            if ($timestamp !== false) {
                return date('Y-m-d', $timestamp);
            }
        } catch (\Exception $e) {
            // Ignorar errores de parsing
        }

        // Intentar formatos específicos
        $formats = ['Y-m-d', 'd/m/Y', 'm/d/Y', 'd-m-Y', 'm-d-Y'];
        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $cleanValue);
            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }

        return null;
    }

    /**
     * Convierte valor a número float.
     *
     * @param mixed $value Valor crudo de celda.
     * @return float|null Número convertido o null si no válido.
     */
    private function parseNumeric($value)
    {
        if ($value === null || $value === '' || trim($value) === '') {
            return null;
        }

        // Limpiar el valor de espacios y caracteres especiales
        $cleanValue = trim($value);
        
        // Si es numérico, convertir
        if (is_numeric($cleanValue)) {
            return (float) $cleanValue;
        }

        return null;
    }

    /**
     * Normaliza un valor textual/numérico a booleano.
     *
     * @param mixed $value Valor crudo de celda.
     * @return bool Verdadero si coincide con valores activos.
     */
    private function parseBoolean($value)
    {
        if (empty($value)) {
            return false;
        }

        $value = strtolower(trim($value));
        return in_array($value, ['1', 'true', 'verdadero', 'sí', 'si', 'activo']);
    }
}