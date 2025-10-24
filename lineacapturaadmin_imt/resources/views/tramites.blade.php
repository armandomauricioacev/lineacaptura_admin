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
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE/Edge */
            touch-action: pan-x;
            overscroll-behavior-x: contain;
        }
        
        /* Ocultar scrollbar del contenedor interno (usamos barras externas) */
        .table-wrapper::-webkit-scrollbar {
            display: none;
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
        
        /* Scrollbar superior sincronizado */
        .table-scroll-top {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 8px;
            height: 12px;
            touch-action: pan-x;
        }
        .table-scroll-top::-webkit-scrollbar {
            height: 8px;
        }
        .table-scroll-top::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        .table-scroll-top::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .table-scroll-top::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Scrollbar inferior sincronizado */
        .table-scroll-bottom {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-top: 8px;
            height: 12px;
            touch-action: pan-x;
        }
        .table-scroll-bottom::-webkit-scrollbar {
            height: 8px;
        }
        .table-scroll-bottom::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        .table-scroll-bottom::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .table-scroll-bottom::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        /* Nuevos estilos para botones, modales y formularios */
        .btn-primary { background: #3b82f6; color: white; padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.2s; }
        .btn-primary:hover { background: #2563eb; }
        .btn-secondary { background: #6b7280; color: white; padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.2s; }
        .btn-secondary:hover { background: #4b5563; }
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50; }
        .modal-content { background: white; border-radius: 8px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; }
        .modal-header { padding: 20px 24px; border-bottom: 1px solid #e5e7eb; }
        .modal-body { padding: 24px; }
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 6px; }
        .form-input { width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; outline: none; transition: border-color 0.2s; }
        .form-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="min-height: 600px;">
                <div class="p-6 text-gray-900 table-container" id="tableContainer">
                    <!-- Alpine.js: funciones clave (Trámites)
                         - normalize(text): Normaliza texto para búsqueda (minúsculas y sin acentos).
                         - filterRows(): Filtra filas por término y actualiza contador visible.
                         - openEditModal(id): Carga datos vía AJAX y abre modal de edición.
                         - openDeleteModal(id, descripcion): Prepara datos y abre modal de eliminación.
                         - validateCreateForm(): Valida campos requeridos del formulario de creación.
                         - Estado: controla showCreateModal, showEditModal, showDeleteModal, showDeleteAllModal, showExcelModal.
                         - Scroll: sincroniza barras superior e inferior usando refs (top/bottom scroll).
                    -->
                    <div x-data="{
                        searchQuery: '',
                        totalRows: {{ $tramites->count() }},
                        visibleRows: {{ $tramites->count() }},
                        showCreateModal: false,
                        showEditModal: false,
                        showDeleteModal: false,
                        showDeleteAllModal: false,
                        showExcelModal: false,
                        editData: {},
                        deleteData: {},
                        createError: false,
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
                        },
                        openEditModal(id) {
                             fetch('/tramites/' + id + '/edit', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                                 .then(r => r.json())
                                 .then(data => { this.editData = data; this.showEditModal = true; })
                                 .catch(() => alert('Error al cargar el trámite'));
                         },
                        openDeleteModal(id, descripcion) {
                            this.deleteData = { id, descripcion };
                            this.showDeleteModal = true;
                        },
                        validateCreateForm() {
                            const form = this.$refs.createForm;
                            let valid = true;
                            const fields = form.querySelectorAll('input:not([type=\'hidden\']), select, textarea');
                            fields.forEach(el => {
                                const name = el.getAttribute('name');
                                if (!name) return;
                                const val = (el.value || '').trim();
                                if (val === '') valid = false;
                            });
                            this.createError = !valid;
                            if (valid) form.submit();
                        },
                        initScrollSync() {
                             this.$nextTick(() => {
                                 const top = this.$refs.topScroll;
                                 const innerTop = this.$refs.topScrollInner;
                                 const bottom = this.$refs.bottomScroll;
                                 const innerBottom = this.$refs.bottomScrollInner;
                                 const wrapper = this.$refs.tableWrapper;
                                 const table = this.$refs.customTable;
                                 
                                 const updateWidths = () => {
                                     if (table) {
                                         const w = table.scrollWidth;
                                         if (innerTop) { innerTop.style.width = w + 'px'; innerTop.style.height = '1px'; }
                                         if (innerBottom) { innerBottom.style.width = w + 'px'; innerBottom.style.height = '1px'; }
                                     }
                                 };
                                 updateWidths();
                                 window.addEventListener('resize', updateWidths);
                                 
                                 let syncing = false;
                                 const setScroll = (el, val) => { if (el && el.scrollLeft !== val) el.scrollLeft = val; };
                                 
                                 const onTopScroll = () => {
                                     if (syncing) return; syncing = true;
                                     setScroll(wrapper, top.scrollLeft);
                                     setScroll(bottom, top.scrollLeft);
                                     syncing = false;
                                 };
                                 const onBottomScroll = () => {
                                     if (syncing) return; syncing = true;
                                     setScroll(wrapper, bottom.scrollLeft);
                                     setScroll(top, bottom.scrollLeft);
                                     syncing = false;
                                 };
                                 const onWrapperScroll = () => {
                                     if (syncing) return; syncing = true;
                                     setScroll(top, wrapper.scrollLeft);
                                     setScroll(bottom, wrapper.scrollLeft);
                                     syncing = false;
                                 };
                                 
                                 if (top) top.addEventListener('scroll', onTopScroll);
                                 if (bottom) bottom.addEventListener('scroll', onBottomScroll);
                                 if (wrapper) wrapper.addEventListener('scroll', onWrapperScroll);
                             });
                         }
                    }" x-init="filterRows(); initScrollSync()">
                    
                    <!-- Mensajes de éxito/error -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-error">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-error">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="w-full min-w-0">
                        
                        <!-- Controles superiores -->
                        <div class="controls-container">
                            <button @click="showCreateModal = true" class="btn-primary">
                                + Agregar Trámite
                            </button>
                            
                            <button @click="showExcelModal = true" class="btn-secondary" style="background: #059669; color: white;">
                                Cargar Excel
                            </button>
                            
                            <button @click="showDeleteAllModal = true" class="btn-secondary" style="background: #dc2626; color: white;">
                                Eliminar Todos
                            </button>
                            
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

                        <!-- Barra de desplazamiento superior -->
                        <div class="table-scroll-top" x-ref="topScroll">
                            <div class="table-scroll-inner" x-ref="topScrollInner"></div>
                        </div>
                        <!-- Tabla de datos -->
                        <div class="table-wrapper" x-ref="tableWrapper">
                            <table class="custom-table" x-ref="customTable">
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
                                        <td>{{ $tramite->obligatorio }}</td>
                                        <td>{{ $tramite->agrupador }}</td>
                                        <td>{{ $tramite->tipo_agrupador }}</td>
                                        <td>{{ $tramite->clave_periodicidad }}</td>
                                        <td>{{ $tramite->clave_periodo }}</td>
                                        <td>{{ $tramite->nombre_monto }}</td>
                                        <td>{{ $tramite->variable }}</td>
                                        <td>${{ number_format($tramite->cuota, 2) }}</td>
                                        <td>{{ $tramite->iva }}</td>
                                        <td>${{ number_format($tramite->monto_iva, 2) }}</td>
                                        <td>{{ $tramite->actualizacion }}</td>
                                        <td>{{ $tramite->recargos }}</td>
                                        <td>{{ $tramite->multa_correccionfiscal }}</td>
                                        <td>{{ $tramite->compensacion }}</td>
                                        <td>{{ $tramite->saldo_favor }}</td>
                                        <td class="text-gray-500">{{ $tramite->created_at->format('d/m/Y') }}</td>
                                        <td class="text-gray-500">{{ $tramite->updated_at->format('d/m/Y') }}</td>
                                        <td style="text-align: center;">
                                            <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                                <button 
                                                     @click="openEditModal({{ $tramite->id }})"
                                                     class="btn-edit"
                                                 >
                                                    Editar
                                                </button>
                                                <button 
                                                    @click="openDeleteModal({{ $tramite->id }}, '{{ addslashes($tramite->descripcion) }}')"
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
                         
                         <!-- Barra de desplazamiento inferior -->
                         <div class="table-scroll-bottom" x-ref="bottomScroll">
                             <div class="table-scroll-inner" x-ref="bottomScrollInner"></div>
                         </div>
                         
                         <div class="mt-4">
                            {{ $tramites->links() }}
                        </div>

                        <!-- Modal Crear -->
                        <div x-show="showCreateModal" x-cloak class="modal-overlay" @click.self="showCreateModal = false">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="text-lg font-semibold">Crear Nuevo Trámite</h3>
                                </div>
                                <form method="POST" action="{{ route('tramites.store') }}" class="modal-body" x-ref="createForm" @submit.prevent="validateCreateForm()">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label">Clave Dependencia Siglas *</label>
                                        <input type="text" name="clave_dependencia_siglas" class="form-input" required />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Clave Trámite *</label>
                                        <input type="text" name="clave_tramite" class="form-input" required />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Variante</label>
                                        <input type="text" name="variante" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Descripción *</label>
                                        <input type="text" name="descripcion" class="form-input" required />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Uso reservado</label>
                                        <input type="text" name="tramite_usoreservado" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Fundamento legal</label>
                                        <input type="text" name="fundamento_legal" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                         <label class="form-label">Vigencia trámite de</label>
                                         <input type="date" name="vigencia_tramite_de" class="form-input" placeholder="YYYY-MM-DD" />
                                     </div>
                                     <div class="form-group">
                                         <label class="form-label">Vigencia trámite al</label>
                                         <input type="date" name="vigencia_tramite_al" class="form-input" placeholder="YYYY-MM-DD" />
                                     </div>
                                    <div class="form-group">
                                        <label class="form-label">Vigencia línea captura</label>
                                        <input type="text" name="vigencia_lineacaptura" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Tipo vigencia</label>
                                        <input type="text" name="tipo_vigencia" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Clave contable</label>
                                        <input type="text" name="clave_contable" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Agrupador</label>
                                        <input type="text" name="agrupador" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Tipo agrupador</label>
                                        <input type="text" name="tipo_agrupador" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Clave periodicidad</label>
                                        <input type="text" name="clave_periodicidad" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Clave periodo</label>
                                        <input type="text" name="clave_periodo" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nombre monto</label>
                                        <input type="text" name="nombre_monto" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Cuota</label>
                                        <input type="number" step="0.01" name="cuota" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Monto IVA</label>
                                        <input type="number" step="0.01" name="monto_iva" class="form-input" />
                                    </div>

                                    <!-- Inputs libres pequeños (sin placeholders) y IVA como select -->
                                      <div class="form-group">
                                          <label class="form-label">Obligatorio</label>
                                          <input type="text" name="obligatorio" class="form-input" maxlength="1" />
                                      </div>
                                      <div class="form-group">
                                          <label class="form-label">Variable</label>
                                          <input type="text" name="variable" class="form-input" maxlength="1" />
                                      </div>
                                      <div class="form-group">
                                          <label class="form-label">IVA</label>
                                          <select name="iva" class="form-input">
                                              <option value="" selected>Seleccionar</option>
                                              <option value="1">Sí</option>
                                              <option value="0">No</option>
                                          </select>
                                      </div>
                                      <div class="form-group">
                                          <label class="form-label">Actualización</label>
                                          <input type="text" name="actualizacion" class="form-input" maxlength="1" />
                                      </div>
                                      <div class="form-group">
                                          <label class="form-label">Recargos</label>
                                          <input type="text" name="recargos" class="form-input" maxlength="1" />
                                      </div>
                                      <div class="form-group">
                                          <label class="form-label">Multa corrección fiscal</label>
                                          <input type="text" name="multa_correccionfiscal" class="form-input" maxlength="1" />
                                      </div>
                                      <div class="form-group">
                                          <label class="form-label">Compensación</label>
                                          <input type="text" name="compensacion" class="form-input" maxlength="1" />
                                      </div>
                                      <div class="form-group">
                                          <label class="form-label">Saldo a favor</label>
                                          <input type="text" name="saldo_favor" class="form-input" maxlength="1" />
                                      </div>

                                    <p x-show="createError" class="text-red-600 text-xs mb-3" style="text-align: right;">Campos incompletos, favor de verificar.</p>
                                     <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                                         <button type="button" @click="showCreateModal = false" class="btn-secondary">Cancelar</button>
                                         <button type="submit" class="btn-primary">Crear</button>
                                     </div>
                                </form>
                            </div>
                        </div>

                        <!-- Modal Editar -->
                        <div x-show="showEditModal" x-cloak class="modal-overlay" @click.self="showEditModal = false">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="text-lg font-semibold">Editar Trámite #<span x-text="editData.id"></span></h3>
                                </div>
                                <form method="POST" :action="`/tramites/${editData.id}`" class="modal-body">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label class="form-label">Clave Dependencia Siglas *</label>
                                        <input type="text" name="clave_dependencia_siglas" x-model="editData.clave_dependencia_siglas" class="form-input" required />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Clave Trámite *</label>
                                        <input type="text" name="clave_tramite" x-model="editData.clave_tramite" class="form-input" required />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Variante</label>
                                        <input type="text" name="variante" x-model="editData.variante" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Descripción *</label>
                                        <input type="text" name="descripcion" x-model="editData.descripcion" class="form-input" required />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Uso reservado</label>
                                        <input type="text" name="tramite_usoreservado" x-model="editData.tramite_usoreservado" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Fundamento legal</label>
                                        <input type="text" name="fundamento_legal" x-model="editData.fundamento_legal" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Vigencia trámite de</label>
                                        <input type="date" name="vigencia_tramite_de" x-model="editData.vigencia_tramite_de" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Vigencia trámite al</label>
                                        <input type="date" name="vigencia_tramite_al" x-model="editData.vigencia_tramite_al" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Vigencia línea captura</label>
                                        <input type="text" name="vigencia_lineacaptura" x-model="editData.vigencia_lineacaptura" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Tipo vigencia</label>
                                        <input type="text" name="tipo_vigencia" x-model="editData.tipo_vigencia" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Clave contable</label>
                                        <input type="text" name="clave_contable" x-model="editData.clave_contable" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Agrupador</label>
                                        <input type="text" name="agrupador" x-model="editData.agrupador" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Tipo agrupador</label>
                                        <input type="text" name="tipo_agrupador" x-model="editData.tipo_agrupador" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Clave periodicidad</label>
                                        <input type="text" name="clave_periodicidad" x-model="editData.clave_periodicidad" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Clave periodo</label>
                                        <input type="text" name="clave_periodo" x-model="editData.clave_periodo" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nombre monto</label>
                                        <input type="text" name="nombre_monto" x-model="editData.nombre_monto" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Cuota</label>
                                        <input type="number" step="0.01" name="cuota" x-model="editData.cuota" class="form-input" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Monto IVA</label>
                                        <input type="number" step="0.01" name="monto_iva" x-model="editData.monto_iva" class="form-input" />
                                    </div>

                                    <!-- Inputs libres pequeños para S/N y 0/1 -->
                                     <div class="form-group">
                                         <label class="form-label">Obligatorio (S/N)</label>
                                         <input type="text" name="obligatorio" x-model="editData.obligatorio" class="form-input" placeholder="S/N" maxlength="1" />
                                     </div>
                                     <div class="form-group">
                                         <label class="form-label">Variable (S/N)</label>
                                         <input type="text" name="variable" x-model="editData.variable" class="form-input" placeholder="S/N" maxlength="1" />
                                     </div>
                                     <div class="form-group">
                                         <label class="form-label">IVA (0/1)</label>
                                         <input type="text" name="iva" x-model="editData.iva" class="form-input" placeholder="0/1" maxlength="1" />
                                     </div>
                                     <div class="form-group">
                                         <label class="form-label">Actualización (S/N)</label>
                                         <input type="text" name="actualizacion" x-model="editData.actualizacion" class="form-input" placeholder="S/N" maxlength="1" />
                                     </div>
                                     <div class="form-group">
                                         <label class="form-label">Recargos (S/N)</label>
                                         <input type="text" name="recargos" x-model="editData.recargos" class="form-input" placeholder="S/N" maxlength="1" />
                                     </div>
                                     <div class="form-group">
                                         <label class="form-label">Multa corrección fiscal (S/N)</label>
                                         <input type="text" name="multa_correccionfiscal" x-model="editData.multa_correccionfiscal" class="form-input" placeholder="S/N" maxlength="1" />
                                     </div>
                                     <div class="form-group">
                                         <label class="form-label">Compensación (S/N)</label>
                                         <input type="text" name="compensacion" x-model="editData.compensacion" class="form-input" placeholder="S/N" maxlength="1" />
                                     </div>
                                     <div class="form-group">
                                         <label class="form-label">Saldo a favor (S/N)</label>
                                         <input type="text" name="saldo_favor" x-model="editData.saldo_favor" class="form-input" placeholder="S/N" maxlength="1" />
                                     </div>

                                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                                        <button type="button" @click="showEditModal = false" class="btn-secondary">Cancelar</button>
                                        <button type="submit" class="btn-primary">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Modal Eliminar -->
                        <div x-show="showDeleteModal" x-cloak class="modal-overlay" @click.self="showDeleteModal = false">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="text-lg font-semibold">Eliminar Trámite #<span x-text="deleteData.id"></span></h3>
                                </div>
                                <div class="modal-body">
                                    <p class="text-gray-700 mb-6">¿Está seguro de que desea eliminar el trámite "<span x-text="deleteData.descripcion" class="font-semibold"></span>"?</p>
                                    <p class="text-sm text-red-600 mb-6">Esta acción no se puede deshacer.</p>
                                    <form method="POST" :action="`/tramites/${deleteData.id}`" style="display: flex; justify-content: flex-end; gap: 12px;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" @click="showDeleteModal = false" class="btn-secondary">Cancelar</button>
                                        <button type="submit" class="btn-delete">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal de carga de Excel -->
                        <div x-show="showExcelModal" x-cloak class="modal-overlay" @click.self="showExcelModal = false">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="text-lg font-semibold">Cargar Trámites desde Excel</h3>
                                </div>
                                <form method="POST" action="{{ route('excel.upload-tramites') }}" enctype="multipart/form-data" class="modal-body">
                                    @csrf
                                    <div class="mb-4">
                                        <p class="text-gray-700 mb-4">Seleccione un archivo Excel (.xlsx) con los datos de los trámites.</p>
                                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                            <p class="text-sm text-yellow-800">
                                                <strong>Advertencia:</strong> Al cargar el archivo Excel, todos los trámites existentes serán eliminados y reemplazados por los nuevos datos.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="excel_file" class="form-label">Archivo Excel *</label>
                                        <input type="file" id="excel_file" name="excel_file" accept=".xlsx,.xls" required class="form-input">
                                    </div>
                                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                                        <button type="button" @click="showExcelModal = false" class="btn-secondary">Cancelar</button>
                                        <button type="submit" class="btn-primary" style="background: #059669;">Cargar Excel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
 
                        <!-- Modal para eliminar TODOS los trámites -->
                        <div x-show="showDeleteAllModal" x-cloak class="modal-overlay" @click.self="showDeleteAllModal = false">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3>Confirmar Eliminación Total</h3>
                                </div>
                                <form method="POST" action="{{ route('tramites.destroy-all') }}" class="modal-body">
                                    @csrf
                                    @method('DELETE')
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                        <p class="text-red-800 font-semibold mb-2">
                                            <strong>ADVERTENCIA CRÍTICA:</strong>
                                        </p>
                                        <p class="text-red-700 mb-2">
                                            Esta acción eliminará <strong>TODOS</strong> los trámites de la base de datos de forma <strong>PERMANENTE</strong>.
                                        </p>
                                        <p class="text-red-700 mb-2">
                                            • Se eliminarán <strong>{{ $tramites->total() }}</strong> trámites en total
                                        </p>
                                        <p class="text-red-700 mb-2">
                                            • Los IDs se reiniciarán desde 1
                                        </p>
                                        <p class="text-red-700 font-semibold">
                                            • Esta acción <strong>NO SE PUEDE DESHACER</strong>
                                        </p>
                                    </div>
                                    <p class="text-gray-700 mb-4">
                                        Si está seguro de que desea continuar, escriba <strong>"ELIMINAR TODO"</strong> en el campo de abajo:
                                    </p>
                                    <div class="form-group">
                                        <input 
                                            type="text" 
                                            id="confirmText" 
                                            placeholder="Escriba: ELIMINAR TODO" 
                                            class="form-input"
                                            required
                                            oninput="document.getElementById('confirmDeleteAll').disabled = this.value !== 'ELIMINAR TODO'"
                                        />
                                    </div>
                                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                                        <button type="button" @click="showDeleteAllModal = false" class="btn-secondary">Cancelar</button>
                                        <button type="submit" id="confirmDeleteAll" disabled class="btn-delete" style="opacity: 0.5;">
                                            Eliminar Todos los Trámites
                                        </button>
                                    </div>
                                </form>
                            </div>
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
            
            // Mejorar la funcionalidad del botón de confirmación
            const confirmInput = document.getElementById('confirmText');
            const confirmButton = document.getElementById('confirmDeleteAll');
            
            if (confirmInput && confirmButton) {
                confirmInput.addEventListener('input', function() {
                    if (this.value === 'ELIMINAR TODO') {
                        confirmButton.disabled = false;
                        confirmButton.style.opacity = '1';
                        confirmButton.style.cursor = 'pointer';
                    } else {
                        confirmButton.disabled = true;
                        confirmButton.style.opacity = '0.5';
                        confirmButton.style.cursor = 'not-allowed';
                    }
                });
            }
        });
    </script>
 </x-app-layout>