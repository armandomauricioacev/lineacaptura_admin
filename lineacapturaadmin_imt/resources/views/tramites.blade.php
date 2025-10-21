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
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="min-height: 600px;">
                <div class="p-6 text-gray-900 min-w-full table-container" id="tableContainer">
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
                    }" 
                    x-init="$watch('searchQuery', () => filterRows())" 
                    class="w-full min-w-0">
                    
                    <!-- Controles de búsqueda y contador -->
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
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clave dependencia siglas</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clave trámite</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variante</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uso reservado</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fundamento legal</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vigencia trámite de</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vigencia trámite al</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vigencia línea captura</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo vigencia</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clave contable</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Obligatorio</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agrupador</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo agrupador</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clave periodicidad</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clave periodo</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre monto</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variable</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cuota</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IVA</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto IVA</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actualización</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recargos</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Multa corrección fiscal</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Compensación</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo a favor</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Creado</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actualizado</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody x-ref="tableBody" class="bg-white divide-y divide-gray-200">
                                @forelse($tramites as $t)
                                    <tr x-data="{ openEdit: false, openDelete: false }">
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->id }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->clave_dependencia_siglas }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->clave_tramite }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->variante }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->descripcion }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->tramite_usoreservado }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 max-w-xs truncate" title="{{ Str::limit($t->fundamento_legal, 100) }}">{{ Str::limit($t->fundamento_legal, 50) }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->vigencia_tramite_de }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->vigencia_tramite_al }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->vigencia_lineacaptura }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->tipo_vigencia }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->clave_contable }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->obligatorio }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->agrupador }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->tipo_agrupador }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->clave_periodicidad }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->clave_periodo }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->nombre_monto }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->variable }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->cuota }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->iva }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->monto_iva }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->actualizacion }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->recargos }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->multa_correccionfiscal }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->compensacion }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $t->saldo_favor }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500">{{ optional($t->created_at)->format('Y-m-d H:i') }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500">{{ optional($t->updated_at)->format('Y-m-d H:i') }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            <button @click="openEdit = true" class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white text-xs font-medium rounded hover:bg-indigo-700">Editar</button>
                                            <button @click="openDelete = true" class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700 ms-2">Eliminar</button>

                                            <!-- Modal Editar -->
                                            <div x-show="openEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
                                                <div @click="openEdit = false" class="absolute inset-0 bg-black bg-opacity-50"></div>
                                                <div class="bg-white w-full max-w-4xl max-h-[85vh] overflow-y-auto rounded shadow-lg z-10">
                                                    <div class="px-6 py-4 border-b">
                                                        <h3 class="text-lg font-semibold">Editar trámite #{{ $t->id }}</h3>
                                                    </div>
                                                    <form method="POST" action="{{ route('tramites.update', $t) }}" class="px-6 py-4">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Clave dependencia siglas</label>
                                                                <input type="text" name="clave_dependencia_siglas" value="{{ $t->clave_dependencia_siglas }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Clave trámite</label>
                                                                <input type="text" name="clave_tramite" value="{{ $t->clave_tramite }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Variante</label>
                                                                <input type="text" name="variante" value="{{ $t->variante }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div class="md:col-span-2">
                                                                <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                                                <textarea name="descripcion" class="mt-1 block w-full rounded border-gray-300" rows="3">{{ $t->descripcion }}</textarea>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Uso reservado</label>
                                                                <input type="text" name="tramite_usoreservado" value="{{ $t->tramite_usoreservado }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div class="md:col-span-2">
                                                                <label class="block text-sm font-medium text-gray-700">Fundamento legal</label>
                                                                <textarea name="fundamento_legal" class="mt-1 block w-full rounded border-gray-300" rows="3">{{ $t->fundamento_legal }}</textarea>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Vigencia trámite de</label>
                                                                <input type="text" name="vigencia_tramite_de" value="{{ $t->vigencia_tramite_de }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Vigencia trámite al</label>
                                                                <input type="text" name="vigencia_tramite_al" value="{{ $t->vigencia_tramite_al }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Vigencia línea captura</label>
                                                                <input type="text" name="vigencia_lineacaptura" value="{{ $t->vigencia_lineacaptura }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Tipo vigencia</label>
                                                                <input type="text" name="tipo_vigencia" value="{{ $t->tipo_vigencia }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Clave contable</label>
                                                                <input type="text" name="clave_contable" value="{{ $t->clave_contable }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Obligatorio</label>
                                                                <input type="text" name="obligatorio" value="{{ $t->obligatorio }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Agrupador</label>
                                                                <input type="text" name="agrupador" value="{{ $t->agrupador }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Tipo agrupador</label>
                                                                <input type="text" name="tipo_agrupador" value="{{ $t->tipo_agrupador }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Clave periodicidad</label>
                                                                <input type="text" name="clave_periodicidad" value="{{ $t->clave_periodicidad }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Clave periodo</label>
                                                                <input type="text" name="clave_periodo" value="{{ $t->clave_periodo }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Nombre monto</label>
                                                                <input type="text" name="nombre_monto" value="{{ $t->nombre_monto }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Variable</label>
                                                                <input type="text" name="variable" value="{{ $t->variable }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Cuota</label>
                                                                <input type="text" name="cuota" value="{{ $t->cuota }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">IVA</label>
                                                                <input type="text" name="iva" value="{{ $t->iva }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Monto IVA</label>
                                                                <input type="text" name="monto_iva" value="{{ $t->monto_iva }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Actualización</label>
                                                                <input type="text" name="actualizacion" value="{{ $t->actualizacion }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Recargos</label>
                                                                <input type="text" name="recargos" value="{{ $t->recargos }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Multa corrección fiscal</label>
                                                                <input type="text" name="multa_correccionfiscal" value="{{ $t->multa_correccionfiscal }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Compensación</label>
                                                                <input type="text" name="compensacion" value="{{ $t->compensacion }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Saldo a favor</label>
                                                                <input type="text" name="saldo_favor" value="{{ $t->saldo_favor }}" class="mt-1 block w-full rounded border-gray-300" />
                                                            </div>
                                                        </div>
                                                        <div class="flex justify-end gap-2 mt-4">
                                                            <button type="button" @click="openEdit = false" class="px-4 py-2 rounded border">Cancelar</button>
                                                            <button type="submit" class="px-4 py-2 rounded bg-indigo-600 text-white">Guardar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <!-- Modal Eliminar -->
                                            <div x-show="openDelete" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
                                                <div @click="openDelete = false" class="absolute inset-0 bg-black bg-opacity-50"></div>
                                                <div class="bg-white w-full max-w-md rounded shadow-lg z-10">
                                                    <div class="px-6 py-4 border-b">
                                                        <h3 class="text-lg font-semibold">Eliminar trámite #{{ $t->id }}</h3>
                                                    </div>
                                                    <div class="px-6 py-4">
                                                        <p class="text-sm text-gray-700">¿Confirma eliminar el trámite: "{{ $t->descripcion }}"?</p>
                                                    </div>
                                                    <form method="POST" action="{{ route('tramites.destroy', $t) }}" class="px-6 pb-4 flex justify-end gap-2">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" @click="openDelete = false" class="px-4 py-2 rounded border">Cancelar</button>
                                                        <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white">Eliminar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="30" class="px-4 py-6 text-center text-sm text-gray-500">No hay registros de trámites.</td>
                                    </tr>
                                @endforelse
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