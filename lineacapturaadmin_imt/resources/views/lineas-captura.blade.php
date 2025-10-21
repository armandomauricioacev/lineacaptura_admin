<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Líneas de captura') }}
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
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="min-height: 600px;">
                <div class="p-6 text-gray-900 min-w-full table-container" id="tableContainer">
                    <div x-data="{
                        openGenerado: false,
                        openRecibido: false,
                        openHtml: false,
                        openErrores: false,
                        selectedGenerado: null,
                        selectedRecibido: null,
                        selectedHtml: null,
                        selectedErrores: null,
                        searchQuery: '',
                        totalRows: {{ $lineas->count() }},
                        visibleRows: {{ $lineas->count() }},
                        showFilters: false,
                        filters: {
                            tipoPersona: '',
                            importeMin: '',
                            importeMax: '',
                            fechaDesde: '',
                            fechaHasta: ''
                        },
                        formatJson(value) {
                            if (!value) return 'Sin datos';
                            try {
                                const parsed = typeof value === 'string' ? JSON.parse(value) : value;
                                return JSON.stringify(parsed, null, 2);
                            } catch (e) {
                                return String(value || 'Error al formatear JSON');
                            }
                        },
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
                                let matchesFilters = true;
                                
                                if (query) {
                                    const rowText = Array.from(cells).map(cell => this.normalize(cell.textContent)).join(' ');
                                    matchesSearch = rowText.includes(query);
                                }
                                
                                // Filtro por tipo de persona (M o F)
                                if (this.filters.tipoPersona && cells[1]) {
                                    const tipoPersona = this.normalize(cells[1].textContent).trim();
                                    matchesFilters = matchesFilters && tipoPersona === this.normalize(this.filters.tipoPersona);
                                }
                                
                                // Filtro por importe total (columna 13)
                                if ((this.filters.importeMin || this.filters.importeMax) && cells[13]) {
                                    const importe = parseFloat(cells[13].textContent.replace(/[^0-9.-]/g, '')) || 0;
                                    if (this.filters.importeMin && importe < parseFloat(this.filters.importeMin)) {
                                        matchesFilters = false;
                                    }
                                    if (this.filters.importeMax && importe > parseFloat(this.filters.importeMax)) {
                                        matchesFilters = false;
                                    }
                                }
                                
                                // Filtro por fechas (columna 18 - creado)
                                if ((this.filters.fechaDesde || this.filters.fechaHasta) && cells[18]) {
                                    const fechaTexto = cells[18].textContent.trim();
                                    const fecha = new Date(fechaTexto);
                                    if (!isNaN(fecha.getTime())) {
                                        if (this.filters.fechaDesde) {
                                            const fechaDesde = new Date(this.filters.fechaDesde);
                                            if (fecha < fechaDesde) {
                                                matchesFilters = false;
                                            }
                                        }
                                        if (this.filters.fechaHasta) {
                                            const fechaHasta = new Date(this.filters.fechaHasta);
                                            fechaHasta.setHours(23, 59, 59, 999);
                                            if (fecha > fechaHasta) {
                                                matchesFilters = false;
                                            }
                                        }
                                    }
                                }
                                
                                if (matchesSearch && matchesFilters) {
                                    row.style.display = '';
                                    visible++;
                                } else {
                                    row.style.display = 'none';
                                }
                            });
                            
                            this.visibleRows = visible;
                        },
                        clearFilters() {
                            this.filters.tipoPersona = '';
                            this.filters.importeMin = '';
                            this.filters.importeMax = '';
                            this.filters.fechaDesde = '';
                            this.filters.fechaHasta = '';
                            this.filterRows();
                        },
                        openModal(type, data) {
                            this['selected' + type] = data;
                            this['open' + type] = true;
                        },
                        closeModal(type) {
                            this['open' + type] = false;
                            setTimeout(() => {
                                this['selected' + type] = null;
                            }, 300);
                        }
                    }" 
                    x-init="$watch('searchQuery', () => filterRows()); $watch('filters', () => filterRows(), { deep: true })" 
                    class="w-full min-w-0">
                    
                    <!-- Controles de búsqueda, filtros y contador -->
                    <div class="mb-6 px-4 flex flex-col sm:flex-row items-stretch sm:items-center gap-4 justify-between min-w-max">
                        <div class="flex items-center gap-4 flex-1">
                            <div class="relative flex-1 max-w-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input 
                                    type="text" 
                                    x-model="searchQuery" 
                                    placeholder="Buscar en todas las columnas..." 
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-300"
                                />
                            </div>
                            
                            <div class="relative">
                                <button 
                                    @click="showFilters = !showFilters"
                                    class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-lg transition-colors duration-200"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                    <span class="text-sm text-gray-700">Filtrar</span>
                                </button>
                            
                                <!-- Panel de filtros -->
                                <div x-show="showFilters" 
                                     x-transition
                                     @click.away="showFilters = false"
                                     class="absolute right-0 top-full mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-xl z-50"
                                     style="min-width: 380px; max-width: 420px;">
                                    <div class="p-4 bg-gray-50">
                                        <h3 class="text-base font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Filtros avanzados</h3>
                                        
                                        <div class="space-y-4">
                                            <!-- Tipo de persona -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de persona</label>
                                                <select x-model="filters.tipoPersona" 
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-0 focus:border-gray-300">
                                                    <option value="">Todos</option>
                                                    <option value="F">Física (F)</option>
                                                    <option value="M">Moral (M)</option>
                                                </select>
                                            </div>

                                            <!-- Rango de importe total -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Rango de importe total</label>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <input type="number" 
                                                           x-model="filters.importeMin" 
                                                           placeholder="Mínimo"
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-0 focus:border-gray-300">
                                                    <input type="number" 
                                                           x-model="filters.importeMax" 
                                                           placeholder="Máximo"
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-0 focus:border-gray-300">
                                                </div>
                                            </div>

                                            <!-- Rango de fechas (creación) -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Rango de fechas (creación)</label>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <div>
                                                        <input type="date" 
                                                               x-model="filters.fechaDesde" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-0 focus:border-gray-300">
                                                        <label class="text-xs text-gray-500 mt-1">Desde</label>
                                                    </div>
                                                    <div>
                                                        <input type="date" 
                                                               x-model="filters.fechaHasta" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-0 focus:border-gray-300">
                                                        <label class="text-xs text-gray-500 mt-1">Hasta</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-between gap-3 mt-4 pt-4 border-t border-gray-200">
                                            <button @click="clearFilters()" 
                                                    class="flex-1 px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-md transition-colors duration-200">
                                                Limpiar
                                            </button>
                                            <button @click="showFilters = false" 
                                                    class="flex-1 px-4 py-2 text-sm text-white bg-gray-600 hover:bg-gray-700 rounded-md transition-colors duration-200">
                                                Aplicar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contador de filas visibles -->
                            <div class="text-sm text-gray-600 whitespace-nowrap">
                                <span x-text="`Mostrando ${visibleRows} de ${totalRows}`"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Scrollbar superior sincronizado -->
                    <div class="mb-2 px-4">
                        <div x-ref="topScroll" 
                             @scroll="$refs.tableContainer.scrollLeft = $refs.topScroll.scrollLeft"
                             class="overflow-x-auto rounded bg-gray-50"
                             style="height: 20px;">
                            <div :style="`width: ${$refs.tableContainer?.scrollWidth || 0}px; height: 1px;`"></div>
                        </div>
                    </div>

                    <!-- Tabla de datos -->
                    <div x-ref="tableContainer"
                         @scroll="$refs.topScroll.scrollLeft = $refs.tableContainer.scrollLeft"
                         class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200 min-w-max">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Tipo persona</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">CURP</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">RFC</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Razón social</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Nombres</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Apellido paterno</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Apellido materno</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Dependencia ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Trámite ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Solicitud</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Importe cuota</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Importe IVA</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Importe total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">JSON generado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Estado pago</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Fecha solicitud</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Fecha vigencia</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Creado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Actualizado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">JSON recibido</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">ID documento</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Tipo pago</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">HTML codificado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Resultado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Línea captura</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Importe SAT</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Fecha vigencia SAT</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Errores SAT</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Fecha respuesta SAT</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Procesado exitosamente</th>
                                </tr>
                            </thead>
                            <tbody x-ref="tableBody" class="bg-white divide-y divide-gray-200">
                                @forelse($lineas as $lc)
                                    <tr>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->id }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->tipo_persona }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->curp ?: 'N/A' }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->rfc }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->razon_social ?: 'N/A' }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->nombres ?: 'N/A' }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->apellido_paterno ?: 'N/A' }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->apellido_materno ?: 'N/A' }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->dependencia_id }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->tramite_id }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->solicitud }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->importe_cuota }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->importe_iva }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->importe_total }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">
                                            <button type="button" 
                                                    class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800" 
                                                    @click="openModal('Generado', @js($lc->json_generado))">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 12c1.5-2.598 4.313-6 9.75-6s8.25 3.402 9.75 6c-1.5 2.598-4.313 6-9.75 6s-8.25-3.402-9.75-6z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span>Ver</span>
                                            </button>
                                        </td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->estado_pago }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->fecha_solicitud }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->fecha_vigencia }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-500">{{ $lc->created_at }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-500">{{ $lc->updated_at }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">
                                            <button type="button" 
                                                    class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800" 
                                                    @click="openModal('Recibido', @js($lc->json_recibido))">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 12c1.5-2.598 4.313-6 9.75-6s8.25 3.402 9.75 6c-1.5 2.598-4.313 6-9.75 6s-8.25-3.402-9.75-6z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span>Ver</span>
                                            </button>
                                        </td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->id_documento }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->tipo_pago }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">
                                            <button type="button" 
                                                    class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800" 
                                                    @click="openModal('Html', @js($lc->html_codificado))">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 12c1.5-2.598 4.313-6 9.75-6s8.25 3.402 9.75 6c-1.5 2.598-4.313 6-9.75 6s-8.25-3.402-9.75-6z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span>Ver</span>
                                            </button>
                                        </td>
                                        <td class="px-6 py-3 text-xs text-gray-900">
                                            <pre class="whitespace-pre-wrap break-words text-xs">{{ $lc->resultado }}</pre>
                                        </td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->linea_captura }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->importe_sat }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->fecha_vigencia_sat }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">
                                            <button type="button" 
                                                    class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800" 
                                                    @click="openModal('Errores', @js($lc->errores_sat))">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 12c1.5-2.598 4.313-6 9.75-6s8.25 3.402 9.75 6c-1.5 2.598-4.313 6-9.75 6s-8.25-3.402-9.75-6z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span>Ver</span>
                                            </button>
                                        </td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->fecha_respuesta_sat }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-900">{{ $lc->procesado_exitosamente }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="31" class="px-4 py-6 text-center text-sm text-gray-500">No hay líneas de captura registradas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal JSON Generado -->
                    <div x-show="openGenerado" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="closeModal('Generado')"
                         class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4" 
                         style="display: none;">
                        <div @click.stop class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[80vh] flex flex-col">
                            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">JSON Generado</h3>
                                <button @click="closeModal('Generado')" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="p-4 overflow-y-auto flex-1">
                                <pre class="bg-gray-50 p-4 rounded text-xs text-gray-800 whitespace-pre-wrap break-words" x-text="formatJson(selectedGenerado)"></pre>
                            </div>
                            <div class="flex justify-end p-4 border-t border-gray-200">
                                <button @click="closeModal('Generado')" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal JSON Recibido -->
                    <div x-show="openRecibido" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="closeModal('Recibido')"
                         class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4" 
                         style="display: none;">
                        <div @click.stop class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[80vh] flex flex-col">
                            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">JSON Recibido</h3>
                                <button @click="closeModal('Recibido')" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="p-4 overflow-y-auto flex-1">
                                <pre class="bg-gray-50 p-4 rounded text-xs text-gray-800 whitespace-pre-wrap break-words" x-text="formatJson(selectedRecibido)"></pre>
                            </div>
                            <div class="flex justify-end p-4 border-t border-gray-200">
                                <button @click="closeModal('Recibido')" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal HTML Codificado -->
                    <div x-show="openHtml" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="closeModal('Html')"
                         class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4" 
                         style="display: none;">
                        <div @click.stop class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[80vh] flex flex-col">
                            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">HTML Codificado</h3>
                                <button @click="closeModal('Html')" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="p-4 overflow-y-auto flex-1">
                                <pre class="bg-gray-50 p-4 rounded text-xs text-gray-800 whitespace-pre-wrap break-all font-mono" x-text="selectedHtml || 'Sin datos'"></pre>
                            </div>
                            <div class="flex justify-end p-4 border-t border-gray-200">
                                <button @click="closeModal('Html')" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Errores SAT -->
                    <div x-show="openErrores" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="closeModal('Errores')"
                         class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4" 
                         style="display: none;">
                        <div @click.stop class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[80vh] flex flex-col">
                            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Errores SAT</h3>
                                <button @click="closeModal('Errores')" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="p-4 overflow-y-auto flex-1">
                                <pre class="bg-red-50 p-4 rounded text-xs text-red-800 whitespace-pre-wrap break-words" x-text="formatJson(selectedErrores)"></pre>
                            </div>
                            <div class="flex justify-end p-4 border-t border-gray-200">
                                <button @click="closeModal('Errores')" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const tableContainer = document.getElementById('tableContainer');
                if (tableContainer) {
                    tableContainer.classList.add('loaded');
                }
            }, 100);
        });
    </script>
</x-app-layout>