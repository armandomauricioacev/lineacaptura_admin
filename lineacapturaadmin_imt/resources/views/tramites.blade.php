<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trámites') }}
        </h2>
    </x-slot>

    <style>
        .table-container {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        .table-container.loaded {
            opacity: 1;
        }
        
        .custom-table {
            width: 100%;
            min-width: 2400px; /* Aumentado para acomodar todas las columnas */
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .custom-table th {
            background: #f8fafc;
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }
        
        .custom-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
            white-space: nowrap;
        }
        
        .custom-table tr:hover {
            background: #f9fafb;
        }
        
        .btn-edit {
            background: #10b981;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s;
            margin-right: 8px;
        }
        
        .btn-edit:hover {
            background: #059669;
        }
        
        .btn-delete {
            background: #ef4444;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-delete:hover {
            background: #dc2626;
        }
        
        .search-container {
            position: relative;
            max-width: 300px;
        }
        
        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: #9ca3af;
        }
        
        .search-input {
            width: 100%;
            padding: 8px 12px 8px 36px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }
        
        .search-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .counter-text {
            color: #6b7280;
            font-size: 14px;
        }
        
        .controls-container {
             display: flex;
             justify-content: flex-start;
             align-items: center;
             margin-bottom: 20px;
             gap: 16px;
         }
        
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        
        /* Estilos para el scrollbar */
        .table-wrapper::-webkit-scrollbar {
            height: 8px;
        }
        
        .table-wrapper::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        .table-wrapper::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        .table-wrapper::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="min-height: 600px;">
                <div class="p-6 text-gray-900 table-container" id="tableContainer">
                    <div x-data="{
                        searchQuery: '',
                        totalRows: {{ $tramites->count() }},
                        visibleRows: {{ $tramites->count() }},
                        normalize(text) {
                            return String(text || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                        },
                        filterRows() {
                            const query = this.normalize(this.searchQuery);
                            const rows = this.$refs.tableBody.querySelectorAll('tr');
                            let visible = 0;
                            
                            rows.forEach(row => {
                                const cells = row.querySelectorAll('td');
                                let matchesSearch = true;
                                
                                if (query) {
                                    const rowText = Array.from(cells).map(cell => this.normalize(cell.textContent)).join(' ');
                                    matchesSearch = rowText.includes(query);
                                }
                                
                                if (matchesSearch) {
                                    row.style.display = '';
                                    visible++;
                                } else {
                                    row.style.display = 'none';
                                }
                            });
                            this.visibleRows = visible;
                        }
                    }" x-init="filterRows()">
                    
                    <div class="w-full min-w-0">
                        
                        <!-- Controles superiores -->
                        <div class="controls-container">
                            <div class="search-container">
                                <svg xmlns="http://www.w3.org/2000/svg" class="search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input 
                                    type="text" 
                                    x-model="searchQuery"
                                    @input="filterRows()"
                                    placeholder="Buscar trámites..." 
                                    class="search-input"
                                />
                            </div>
                            
                            <!-- Contador de filas visibles -->
                            <div class="counter-text">
                                <span x-text="`Mostrando ${visibleRows} de ${totalRows}`"></span>
                            </div>
                        </div>

                        <!-- Tabla de datos -->
                        <div class="table-wrapper">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Clave Dependencia Siglas</th>
                                        <th>Clave Trámite</th>
                                        <th>Variante</th>
                                        <th>Descripción</th>
                                        <th>Uso Reservado</th>
                                        <th>Fundamento Legal</th>
                                        <th>Vigencia Trámite De</th>
                                        <th>Vigencia Trámite Al</th>
                                        <th>Vigencia Línea Captura</th>
                                        <th>Tipo Vigencia</th>
                                        <th>Clave Contable</th>
                                        <th>Obligatorio</th>
                                        <th>Agrupador</th>
                                        <th>Tipo Agrupador</th>
                                        <th>Clave Periodicidad</th>
                                        <th>Clave Periodo</th>
                                        <th>Nombre Monto</th>
                                        <th>Variable</th>
                                        <th>Cuota</th>
                                        <th>IVA</th>
                                        <th>Monto IVA</th>
                                        <th>Actualización</th>
                                        <th>Recargos</th>
                                        <th>Multa Corrección Fiscal</th>
                                        <th>Compensación</th>
                                        <th>Saldo a Favor</th>
                                        <th>Creado</th>
                                        <th>Actualizado</th>
                                        <th style="text-align: center;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody x-ref="tableBody">
                                    @foreach($tramites as $tramite)
                                    <tr data-row>
                                        <td>{{ $tramite->id }}</td>
                                        <td>{{ $tramite->clave_dependencia_siglas }}</td>
                                        <td>{{ $tramite->clave_tramite }}</td>
                                        <td>{{ $tramite->variante }}</td>
                                        <td>{{ $tramite->descripcion }}</td>
                                        <td>{{ $tramite->tramite_usoreservado }}</td>
                                        <td>{{ $tramite->fundamento_legal }}</td>
                                        <td class="text-gray-500">{{ $tramite->vigencia_tramite_de }}</td>
                                        <td class="text-gray-500">{{ $tramite->vigencia_tramite_al }}</td>
                                        <td>{{ $tramite->vigencia_lineacaptura }}</td>
                                        <td>{{ $tramite->tipo_vigencia }}</td>
                                        <td>{{ $tramite->clave_contable }}</td>
                                        <td>{{ $tramite->obligatorio ? 'Sí' : 'No' }}</td>
                                        <td>{{ $tramite->agrupador }}</td>
                                        <td>{{ $tramite->tipo_agrupador }}</td>
                                        <td>{{ $tramite->clave_periodicidad }}</td>
                                        <td>{{ $tramite->clave_periodo }}</td>
                                        <td>{{ $tramite->nombre_monto }}</td>
                                        <td>{{ $tramite->variable ? 'Sí' : 'No' }}</td>
                                        <td>${{ number_format($tramite->cuota, 2) }}</td>
                                        <td>{{ $tramite->iva ? 'Sí' : 'No' }}</td>
                                        <td>${{ number_format($tramite->monto_iva, 2) }}</td>
                                        <td>{{ $tramite->actualizacion ? 'Sí' : 'No' }}</td>
                                        <td>{{ $tramite->recargos ? 'Sí' : 'No' }}</td>
                                        <td>{{ $tramite->multa_correccionfiscal ? 'Sí' : 'No' }}</td>
                                        <td>{{ $tramite->compensacion ? 'Sí' : 'No' }}</td>
                                        <td>{{ $tramite->saldo_favor ? 'Sí' : 'No' }}</td>
                                        <td class="text-gray-500">{{ $tramite->created_at->format('d/m/Y') }}</td>
                                        <td class="text-gray-500">{{ $tramite->updated_at->format('d/m/Y') }}</td>
                                        <td style="text-align: center;">
                                            <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                                <button 
                                                    @click="editTramite({{ $tramite->id }})"
                                                    class="btn-edit"
                                                >
                                                    Editar
                                                </button>
                                                <button 
                                                    @click="deleteTramite({{ $tramite->id }}, '{{ $tramite->descripcion }}')"
                                                    class="btn-delete"
                                                >
                                                    Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $tramites->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add a small delay to ensure all content is loaded
            setTimeout(function() {
                const container = document.getElementById('tableContainer');
                if (container) {
                    container.classList.add('loaded');
                }
            }, 100);
        });
    </script>
</x-app-layout>