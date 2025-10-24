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
        
        .custom-table {
            width: 100%;
            min-width: 3800px;
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
        
        .btn-view {
            background: #3b82f6;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .btn-view:hover {
            background: #2563eb;
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
        
        .btn-delete-action {
            background: #ef4444;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-delete-action:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.3);
        }
        
        .search-container {
            position: relative;
            max-width: 400px;
            flex: 1;
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
            white-space: nowrap;
        }
        
        .controls-container {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-bottom: 20px;
            gap: 16px;
            flex-wrap: wrap;
        }
        
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            scrollbar-width: none;
            -ms-overflow-style: none;
            touch-action: pan-x;
            overscroll-behavior-x: contain;
        }
        
        .table-wrapper::-webkit-scrollbar {
            display: none;
        }
        
        .table-scroll-top, .table-scroll-bottom {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            height: 12px;
            touch-action: pan-x;
        }
        
        .table-scroll-top {
            margin-bottom: 8px;
        }
        
        .table-scroll-bottom {
            margin-top: 8px;
        }
        
        .table-scroll-top::-webkit-scrollbar,
        .table-scroll-bottom::-webkit-scrollbar {
            height: 8px;
        }
        
        .table-scroll-top::-webkit-scrollbar-track,
        .table-scroll-bottom::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        .table-scroll-top::-webkit-scrollbar-thumb,
        .table-scroll-bottom::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        .table-scroll-top::-webkit-scrollbar-thumb:hover,
        .table-scroll-bottom::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        .btn-filter {
            background: #6b7280;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-filter:hover {
            background: #4b5563;
        }
        
        .filter-panel {
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            width: 380px;
            max-height: 500px;
            overflow-y: auto;
            z-index: 50;
        }
        
        .filter-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e5e7eb;
            background: #f8fafc;
            border-radius: 8px 8px 0 0;
        }
        
        .filter-header h3 {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
        }
        
        .filter-body {
            padding: 20px;
        }
        
        .form-group {
            margin-bottom: 16px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }
        
        .form-input, .form-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }
        
        .form-input:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .filter-actions {
            display: flex;
            gap: 12px;
            padding: 16px 20px;
            border-top: 1px solid #e5e7eb;
            background: #f8fafc;
        }
        
        .btn-secondary {
            flex: 1;
            background: #6b7280;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-secondary:hover {
            background: #4b5563;
        }
        
        .btn-primary {
            background: #3b82f6;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-primary:hover {
            background: #2563eb;
        }
        
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50;
        }
        
        .modal-content {
            background: white;
            border-radius: 8px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            max-width: 800px;
            width: 90%;
            max-height: 80vh;
            display: flex;
            flex-direction: column;
        }
        
        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #374151;
        }
        
        .modal-body {
            padding: 24px;
            overflow-y: auto;
            flex: 1;
        }
        
        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }
        
        .btn-close {
            color: #9ca3af;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            transition: color 0.2s;
        }
        
        .btn-close:hover {
            color: #6b7280;
        }
        
        .json-display {
            background: #f8fafc;
            padding: 16px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #374151;
            white-space: pre-wrap;
            word-break: break-word;
            max-height: 500px;
            overflow-y: auto;
        }
        
        .error-display {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .badge-estado {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-pagado {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-pendiente {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-no-pagado {
            background: #fee2e2;
            color: #991b1b;
        }

        .warning-box {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .warning-box-danger {
            background: #fee2e2;
            border: 2px solid #ef4444;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .warning-title {
            font-size: 16px;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .warning-title-danger {
            font-size: 16px;
            font-weight: 700;
            color: #991b1b;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .warning-text {
            color: #78350f;
            font-size: 14px;
            line-height: 1.6;
        }

        .warning-text-danger {
            color: #7f1d1d;
            font-size: 14px;
            line-height: 1.6;
            font-weight: 600;
        }

        .warning-list {
            margin-top: 12px;
            padding-left: 20px;
        }

        .warning-list li {
            color: #78350f;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .filter-summary {
            background: #f9fafb;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .filter-summary-title {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .filter-summary-content {
            font-size: 13px;
            color: #374151;
        }

        .filter-summary-content p {
            margin-bottom: 4px;
        }

        .count-highlight {
            font-size: 14px;
            color: #374151;
            margin-top: 12px;
            font-weight: 600;
            background: white;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="min-height: 600px;">
                <div class="p-6 text-gray-900 table-container" id="tableContainer">
                    <div x-data="{
                        openGenerado: false,
                        openRecibido: false,
                        openHtml: false,
                        openErrores: false,
                        openDeleteModal: false,
                        openDeleteAllModal: false,
                        openDeleteFilteredModal: false,
                        selectedGenerado: null,
                        selectedRecibido: null,
                        selectedHtml: null,
                        selectedErrores: null,
                        deleteId: null,
                        deleteSolicitud: null,
                        totalRows: {{ $totalLineas }},
                        filteredRows: {{ $lineas->count() }},
                        showFilters: false,
                        filters: {
                            tipoPersona: '{{ request('tipo_persona', '') }}',
                            estadoPago: '{{ request('estado_pago', '') }}',
                            importeMin: '{{ request('importe_min', '') }}',
                            importeMax: '{{ request('importe_max', '') }}',
                            fechaDesde: '{{ request('fecha_desde', '') }}',
                            fechaHasta: '{{ request('fecha_hasta', '') }}',
                            orden: '{{ request('orden', 'recientes') }}'
                        },
                        searchValue: '{{ request('search', '') }}',
                        formatJson(value) {
                            if (!value) return 'Sin datos';
                            try {
                                const parsed = typeof value === 'string' ? JSON.parse(value) : value;
                                return JSON.stringify(parsed, null, 2);
                            } catch (e) {
                                return String(value || 'Error al formatear JSON');
                            }
                        },
                        applyFilters() {
                            const params = new URLSearchParams();
                            if (this.filters.tipoPersona) params.append('tipo_persona', this.filters.tipoPersona);
                            if (this.filters.estadoPago) params.append('estado_pago', this.filters.estadoPago);
                            if (this.filters.importeMin) params.append('importe_min', this.filters.importeMin);
                            if (this.filters.importeMax) params.append('importe_max', this.filters.importeMax);
                            if (this.filters.fechaDesde) params.append('fecha_desde', this.filters.fechaDesde);
                            if (this.filters.fechaHasta) params.append('fecha_hasta', this.filters.fechaHasta);
                            if (this.filters.orden) params.append('orden', this.filters.orden);
                            if (this.searchValue) params.append('search', this.searchValue);
                            window.location.href = '/lineas-captura?' + params.toString();
                        },
                        clearFilters() {
                            this.filters = {
                                tipoPersona: '',
                                estadoPago: '',
                                importeMin: '',
                                importeMax: '',
                                fechaDesde: '',
                                fechaHasta: '',
                                orden: 'recientes'
                            };
                            this.searchValue = '';
                            window.location.href = '/lineas-captura';
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
                        },
                        openDelete(id, solicitud) {
                            this.deleteId = id;
                            this.deleteSolicitud = solicitud;
                            this.openDeleteModal = true;
                        },
                        hasActiveFilters() {
                            return this.filters.fechaDesde || this.filters.fechaHasta || 
                                   this.filters.tipoPersona || this.filters.estadoPago ||
                                   this.filters.importeMin || this.filters.importeMax ||
                                   (this.filters.orden && this.filters.orden !== 'recientes') ||
                                   this.searchValue;
                        },
                        getDeleteButtonText() {
                            return this.hasActiveFilters() ? 'Eliminar filtrados' : 'Eliminar TODO';
                        },
                        openDeleteAction() {
                            if (this.hasActiveFilters()) {
                                this.openDeleteFilteredModal = true;
                            } else {
                                this.openDeleteAllModal = true;
                            }
                        },
                        getCounterText() {
                            const hasFilters = this.hasActiveFilters();
                            if (hasFilters) {
                                return `Mostrando ${this.filteredRows} de ${this.totalRows} registros`;
                            } else {
                                return `Mostrando ${this.totalRows} de ${this.totalRows} registros`;
                            }
                        },
                        getFilteredCount() {
                            return this.filteredRows;
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
                    }" x-init="initScrollSync()">
                    
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
                    
                        <div class="w-full min-w-0">
                            <div class="controls-container">
                                <div class="search-container">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <form action="{{ route('lineas-captura') }}" method="GET">
                                        <input type="hidden" name="tipo_persona" :value="filters.tipoPersona">
                                        <input type="hidden" name="estado_pago" :value="filters.estadoPago">
                                        <input type="hidden" name="importe_min" :value="filters.importeMin">
                                        <input type="hidden" name="importe_max" :value="filters.importeMax">
                                        <input type="hidden" name="fecha_desde" :value="filters.fechaDesde">
                                        <input type="hidden" name="fecha_hasta" :value="filters.fechaHasta">
                                        <input type="hidden" name="orden" :value="filters.orden">
                                        <input 
                                            type="text" 
                                            name="search"
                                            x-model="searchValue"
                                            placeholder="Buscar líneas de captura..." 
                                            class="search-input"
                                        />
                                    </form>
                                </div>
                                
                                <div style="position: relative;">
                                    <button @click="showFilters = !showFilters" class="btn-filter">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                        </svg>
                                        Filtros
                                    </button>
                                    
                                    <div x-show="showFilters" 
                                         @click.away="showFilters = false"
                                         x-transition
                                         class="filter-panel"
                                         style="display: none;">
                                        <div class="filter-header">
                                            <h3>Filtros avanzados</h3>
                                        </div>
                                        <div class="filter-body">
                                            <div class="form-group">
                                                <label class="form-label">Ordenar por</label>
                                                <select x-model="filters.orden" class="form-select">
                                                    <option value="recientes">Las más recientes</option>
                                                    <option value="antiguas">Las más antiguas</option>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="form-label">Tipo de persona</label>
                                                <select x-model="filters.tipoPersona" class="form-select">
                                                    <option value="">Todos</option>
                                                    <option value="F">Física (F)</option>
                                                    <option value="M">Moral (M)</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Estado de pago</label>
                                                <select x-model="filters.estadoPago" class="form-select">
                                                    <option value="">Todos</option>
                                                    <option value="pagado">Pagado</option>
                                                    <option value="pendiente">Pendiente</option>
                                                    <option value="no pagado">No pagado</option>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="form-label">Rango de importe total</label>
                                                <div class="grid-2">
                                                    <input type="number" x-model="filters.importeMin" placeholder="Mínimo" class="form-input" step="0.01">
                                                    <input type="number" x-model="filters.importeMax" placeholder="Máximo" class="form-input" step="0.01">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="form-label">Rango de fechas (Vigencia)</label>
                                                <div class="grid-2">
                                                    <input type="date" x-model="filters.fechaDesde" class="form-input">
                                                    <input type="date" x-model="filters.fechaHasta" class="form-input">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="filter-actions">
                                            <button @click="clearFilters()" class="btn-secondary">Limpiar</button>
                                            <button @click="showFilters = false; applyFilters();" class="btn-primary">Aplicar</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <button @click="openDeleteAction()" class="btn-delete-action">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span x-text="getDeleteButtonText()"></span>
                                </button>
                                
                                <div class="counter-text">
                                    <span x-text="getCounterText()"></span>
                                </div>
                            </div>

                            <div class="table-scroll-top" x-ref="topScroll">
                                <div class="table-scroll-inner" x-ref="topScrollInner"></div>
                            </div>

                            <div class="table-wrapper" x-ref="tableWrapper">
                                <table class="custom-table"
                                x-ref="customTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tipo persona</th>
                                            <th>CURP</th>
                                            <th>RFC</th>
                                            <th>Razón social</th>
                                            <th>Nombres</th>
                                            <th>Apellido paterno</th>
                                            <th>Apellido materno</th>
                                            <th>Dependencia ID</th>
                                            <th>Trámite ID</th>
                                            <th>Solicitud</th>
                                            <th>Importe cuota</th>
                                            <th>Importe IVA</th>
                                            <th>Importe total</th>
                                            <th>JSON generado</th>
                                            <th>Estado pago</th>
                                            <th>Fecha solicitud</th>
                                            <th>Fecha vigencia</th>
                                            <th>Creado</th>
                                            <th>Actualizado</th>
                                            <th>JSON recibido</th>
                                            <th>ID documento</th>
                                            <th>Tipo pago</th>
                                            <th>HTML codificado</th>
                                            <th>Resultado</th>
                                            <th>Línea captura</th>
                                            <th>Importe SAT</th>
                                            <th>Fecha vigencia SAT</th>
                                            <th>Errores SAT</th>
                                            <th>Fecha respuesta SAT</th>
                                            <th>Procesado exitosamente</th>
                                            <th style="text-align: center;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody x-ref="tableBody">
                                        @forelse($lineas as $lc)
                                        <tr data-row>
                                            <td>{{ $lc->id }}</td>
                                            <td>{{ $lc->tipo_persona }}</td>
                                            <td>{{ $lc->curp ?: 'N/A' }}</td>
                                            <td>{{ $lc->rfc }}</td>
                                            <td>{{ $lc->razon_social ?: 'N/A' }}</td>
                                            <td>{{ $lc->nombres ?: 'N/A' }}</td>
                                            <td>{{ $lc->apellido_paterno ?: 'N/A' }}</td>
                                            <td>{{ $lc->apellido_materno ?: 'N/A' }}</td>
                                            <td>{{ $lc->dependencia_id }}</td>
                                            <td>{{ $lc->tramite_id }}</td>
                                            <td>{{ $lc->solicitud }}</td>
                                            <td>${{ number_format($lc->importe_cuota, 2) }}</td>
                                            <td>${{ number_format($lc->importe_iva, 2) }}</td>
                                            <td>${{ number_format($lc->importe_total, 2) }}</td>
                                            <td>
                                                <button @click="openModal('Generado', @js($lc->json_generado))" class="btn-view">
                                                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Ver
                                                </button>
                                            </td>
                                            <td>
                                                <span class="badge-estado badge-{{ strtolower(str_replace(' ', '-', $lc->estado_pago)) }}">
                                                    {{ $lc->estado_pago }}
                                                </span>
                                            </td>
                                            <td>{{ $lc->fecha_solicitud }}</td>
                                            <td>{{ $lc->fecha_vigencia }}</td>
                                            <td style="color: #6b7280;">{{ $lc->created_at }}</td>
                                            <td style="color: #6b7280;">{{ $lc->updated_at }}</td>
                                            <td>
                                                <button @click="openModal('Recibido', @js($lc->json_recibido))" class="btn-view">
                                                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Ver
                                                </button>
                                            </td>
                                            <td>{{ $lc->id_documento ?: 'N/A' }}</td>
                                            <td>{{ $lc->tipo_pago ?: 'N/A' }}</td>
                                            <td>
                                                <button @click="openModal('Html', @js($lc->html_codificado))" class="btn-view">
                                                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Ver
                                                </button>
                                            </td>
                                            <td>{{ $lc->resultado ?: 'N/A' }}</td>
                                            <td>{{ $lc->linea_captura ?: 'N/A' }}</td>
                                            <td>{{ $lc->importe_sat ?: 'N/A' }}</td>
                                            <td>{{ $lc->fecha_vigencia_sat ?: 'N/A' }}</td>
                                            <td>
                                                <button @click="openModal('Errores', @js($lc->errores_sat))" class="btn-view">
                                                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Ver
                                                </button>
                                            </td>
                                            <td>{{ $lc->fecha_respuesta_sat ?: 'N/A' }}</td>
                                            <td>{{ $lc->procesado_exitosamente }}</td>
                                            <td style="text-align: center;">
                                                <button @click="openDelete({{ $lc->id }}, '{{ $lc->solicitud }}')" class="btn-delete">
                                                    Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="32" style="text-align: center; padding: 24px; color: #6b7280;">
                                                No hay líneas de captura registradas.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="table-scroll-bottom" x-ref="bottomScroll">
                                <div class="table-scroll-inner" x-ref="bottomScrollInner"></div>
                            </div>

                            <!-- Modal JSON Generado -->
                            <div x-show="openGenerado" 
                                 x-cloak
                                 @click="closeModal('Generado')"
                                 class="modal-overlay"
                                 style="display: none;">
                                <div @click.stop class="modal-content">
                                    <div class="modal-header">
                                        <h3>JSON Generado</h3>
                                        <button @click="closeModal('Generado')" class="btn-close">
                                            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <pre class="json-display" x-text="formatJson(selectedGenerado)"></pre>
                                    </div>
                                    <div class="modal-footer">
                                        <button @click="closeModal('Generado')" class="btn-primary">Cerrar</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal JSON Recibido -->
                            <div x-show="openRecibido" 
                                 x-cloak
                                 @click="closeModal('Recibido')"
                                 class="modal-overlay"
                                 style="display: none;">
                                <div @click.stop class="modal-content">
                                    <div class="modal-header">
                                        <h3>JSON Recibido</h3>
                                        <button @click="closeModal('Recibido')" class="btn-close">
                                            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <pre class="json-display" x-text="formatJson(selectedRecibido)"></pre>
                                    </div>
                                    <div class="modal-footer">
                                        <button @click="closeModal('Recibido')" class="btn-primary">Cerrar</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal HTML Codificado -->
                            <div x-show="openHtml" 
                                 x-cloak
                                 @click="closeModal('Html')"
                                 class="modal-overlay"
                                 style="display: none;">
                                <div @click.stop class="modal-content">
                                    <div class="modal-header">
                                        <h3>HTML Codificado</h3>
                                        <button @click="closeModal('Html')" class="btn-close">
                                            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <pre class="json-display" x-text="selectedHtml || 'Sin datos'"></pre>
                                    </div>
                                    <div class="modal-footer">
                                        <button @click="closeModal('Html')" class="btn-primary">Cerrar</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Errores SAT -->
                            <div x-show="openErrores" 
                                 x-cloak
                                 @click="closeModal('Errores')"
                                 class="modal-overlay"
                                 style="display: none;">
                                <div @click.stop class="modal-content">
                                    <div class="modal-header">
                                        <h3>Errores SAT</h3>
                                        <button @click="closeModal('Errores')" class="btn-close">
                                            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <pre class="json-display error-display" x-text="formatJson(selectedErrores)"></pre>
                                    </div>
                                    <div class="modal-footer">
                                        <button @click="closeModal('Errores')" class="btn-primary">Cerrar</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Eliminar Individual -->
                            <div x-show="openDeleteModal" 
                                 x-cloak
                                 @click="openDeleteModal = false"
                                 class="modal-overlay"
                                 style="display: none;">
                                <div @click.stop class="modal-content" style="max-width: 500px;">
                                    <div class="modal-header">
                                        <h3>Eliminar Línea de Captura</h3>
                                        <button @click="openDeleteModal = false" class="btn-close">
                                            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p style="color: #374151; margin-bottom: 16px;">¿Está seguro de que desea eliminar la línea de captura con solicitud <strong x-text="deleteSolicitud"></strong>?</p>
                                        <p style="font-size: 14px; color: #dc2626;">Esta acción no se puede deshacer.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button @click="openDeleteModal = false" class="btn-secondary">Cancelar</button>
                                        <form :action="`/lineas-captura/${deleteId}`" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete">Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Eliminar TODO -->
                            <div x-show="openDeleteAllModal" 
                                 x-cloak
                                 @click="openDeleteAllModal = false"
                                 class="modal-overlay"
                                 style="display: none;">
                                <div @click.stop class="modal-content" style="max-width: 600px;">
                                    <div class="modal-header">
                                        <h3 style="color: #991b1b;">⚠️ Eliminar TODAS las Líneas de Captura</h3>
                                        <button @click="openDeleteAllModal = false" class="btn-close">
                                            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="warning-box-danger">
                                            <div class="warning-title-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                ADVERTENCIA CRÍTICA
                                            </div>
                                            <p class="warning-text-danger">
                                                Está a punto de eliminar TODAS las líneas de captura de la base de datos.
                                            </p>
                                        </div>

                                        <p style="color: #374151; margin-bottom: 16px; font-size: 15px; line-height: 1.6;">
                                            Esta acción eliminará <strong style="color: #991b1b; font-size: 18px;" x-text="totalRows"></strong> líneas de captura de forma permanente.
                                        </p>

                                        <div class="warning-box">
                                            <div class="warning-title">
                                                <svg xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Consecuencias de esta acción:
                                            </div>
                                            <ul class="warning-list">
                                                <li>✗ Se eliminarán <strong>TODOS</strong> los registros de líneas de captura</li>
                                                <li>✗ Los datos NO se podrán recuperar</li>
                                                <li>✗ Se perderá todo el historial de pagos y solicitudes</li>
                                                <li>✓ El contador de ID se reiniciará a 1</li>
                                                <li>✓ La base de datos quedará completamente limpia</li>
                                            </ul>
                                        </div>

                                        <p style="font-size: 15px; color: #991b1b; font-weight: 700; margin-top: 20px; text-align: center; background: #fee2e2; padding: 12px; border-radius: 6px; border: 2px solid #ef4444;">
                                            ⚠️ ESTA ACCIÓN ES IRREVERSIBLE ⚠️
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button @click="openDeleteAllModal = false" class="btn-secondary">Cancelar</button>
                                        <form action="{{ route('lineas-captura.delete-all') }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete" style="background: #7f1d1d; font-weight: 700;">
                                                Sí, eliminar TODO
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Eliminar por Filtros -->
                            <div x-show="openDeleteFilteredModal" 
                                 x-cloak
                                 @click="openDeleteFilteredModal = false"
                                 class="modal-overlay"
                                 style="display: none;">
                                <div @click.stop class="modal-content" style="max-width: 600px;">
                                    <div class="modal-header">
                                        <h3>Eliminar Registros Filtrados</h3>
                                        <button @click="openDeleteFilteredModal = false" class="btn-close">
                                            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p style="color: #374151; margin-bottom: 16px; font-size: 15px;">
                                            ¿Está seguro de que desea eliminar todas las líneas de captura que coinciden con los filtros aplicados?
                                        </p>
                                        
                                        <div class="filter-summary">
                                            <p class="filter-summary-title">📋 Filtros aplicados:</p>
                                            <div class="filter-summary-content">
                                                <p x-show="searchValue" style="margin-bottom: 6px;">
                                                    <strong>🔍 Búsqueda:</strong> <span x-text="searchValue"></span>
                                                </p>
                                                <p x-show="filters.orden && filters.orden !== 'recientes'" style="margin-bottom: 6px;">
                                                    <strong>📊 Orden:</strong> <span x-text="filters.orden === 'antiguas' ? 'Las más antiguas' : filters.orden"></span>
                                                </p>
                                                <p x-show="filters.tipoPersona" style="margin-bottom: 6px;">
                                                    <strong>👤 Tipo persona:</strong> <span x-text="filters.tipoPersona === 'F' ? 'Física (F)' : 'Moral (M)'"></span>
                                                </p>
                                                <p x-show="filters.estadoPago" style="margin-bottom: 6px;">
                                                    <strong>💳 Estado pago:</strong> <span x-text="filters.estadoPago"></span>
                                                </p>
                                                <p x-show="filters.importeMin || filters.importeMax" style="margin-bottom: 6px;">
                                                    <strong>💰 Rango importe:</strong> 
                                                    $<span x-text="filters.importeMin || '0'"></span> - $<span x-text="filters.importeMax || '∞'"></span>
                                                </p>
                                                <p x-show="filters.fechaDesde" style="margin-bottom: 6px;">
                                                    <strong>📅 Fecha desde:</strong> <span x-text="filters.fechaDesde"></span>
                                                </p>
                                                <p x-show="filters.fechaHasta" style="margin-bottom: 6px;">
                                                    <strong>📅 Fecha hasta:</strong> <span x-text="filters.fechaHasta"></span>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="count-highlight">
                                            <strong>🗑️ Registros que serán eliminados:</strong> 
                                            <span style="color: #dc2626; font-size: 16px;" x-text="filteredRows"></span> 
                                            de <span x-text="totalRows"></span>
                                        </div>

                                        <p style="font-size: 14px; color: #dc2626; font-weight: 600; margin-top: 16px; padding: 10px; background: #fee2e2; border-radius: 6px; border-left: 4px solid #ef4444;">
                                            ⚠️ Esta acción no se puede deshacer y eliminará múltiples registros.
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button @click="openDeleteFilteredModal = false" class="btn-secondary">Cancelar</button>
                                        <form action="{{ route('lineas-captura.delete-filtered') }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="tipo_persona" :value="filters.tipoPersona">
                                            <input type="hidden" name="estado_pago" :value="filters.estadoPago">
                                            <input type="hidden" name="importe_min" :value="filters.importeMin">
                                            <input type="hidden" name="importe_max" :value="filters.importeMax">
                                            <input type="hidden" name="fecha_desde" :value="filters.fechaDesde">
                                            <input type="hidden" name="fecha_hasta" :value="filters.fechaHasta">
                                            <input type="hidden" name="search" :value="searchValue">
                                            <button type="submit" class="btn-delete">Eliminar Filtrados</button>
                                        </form>
                                    </div>
                                </div>
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
                const container = document.getElementById('tableContainer');
                if (container) {
                    container.classList.add('loaded');
                }
            }, 100);
        });
    </script>
</x-app-layout>