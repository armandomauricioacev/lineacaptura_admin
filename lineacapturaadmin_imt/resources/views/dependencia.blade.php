<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dependencias') }}
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
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .custom-table th {
            background: #f8fafc;
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .custom-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .custom-table tr:hover {
            background: #f9fafb;
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
        
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50;
        }
        
        .modal-content {
            background: white;
            border-radius: 8px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .modal-body {
            padding: 24px;
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
        
        .form-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }
        
        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .btn-secondary {
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
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="min-height: 600px;">
                <div class="p-6 text-gray-900 min-w-full table-container" id="tableContainer">
                    <div x-data="{
                        searchQuery: '',
                        totalRows: {{ $dependencias->count() }},
                        visibleRows: {{ $dependencias->count() }},
                        showCreateModal: false,
                        showEditModal: false,
                        showDeleteModal: false,
                        editData: {},
                        deleteData: {},
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
                        openEditModal(id, nombre, clave, unidad) {
                            this.editData = { id, nombre, clave, unidad };
                            this.showEditModal = true;
                        },
                        openDeleteModal(id, nombre) {
                            this.deleteData = { id, nombre };
                            this.showDeleteModal = true;
                        }
                    }" x-init="filterRows()">
                    
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

                    <!-- Controles superiores -->
                    <div class="controls-container">
                        <button @click="showCreateModal = true" class="btn-primary">
                            + Agregar Dependencia
                        </button>
                        
                        <div class="search-container">
                            <svg xmlns="http://www.w3.org/2000/svg" class="search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input 
                                type="text" 
                                x-model="searchQuery"
                                @input="filterRows()"
                                placeholder="Buscar dependencias..." 
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
                                    <th>Nombre</th>
                                    <th>Clave dependencia</th>
                                    <th>Unidad administrativa</th>
                                    <th>Creado</th>
                                    <th>Actualizado</th>
                                    <th style="text-align: center;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody x-ref="tableBody">
                                @forelse($dependencias as $dep)
                                <tr data-row>
                                    <td>{{ $dep->id }}</td>
                                    <td>{{ $dep->nombre }}</td>
                                    <td>{{ $dep->clave_dependencia }}</td>
                                    <td>{{ $dep->unidad_administrativa ?? '-' }}</td>
                                    <td class="text-gray-500">{{ optional($dep->created_at)->format('Y-m-d H:i') }}</td>
                                    <td class="text-gray-500">{{ optional($dep->updated_at)->format('Y-m-d H:i') }}</td>
                                    <td style="text-align: center;">
                                        <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                            <button @click="openEditModal({{ $dep->id }}, '{{ addslashes($dep->nombre) }}', '{{ addslashes($dep->clave_dependencia) }}', '{{ addslashes($dep->unidad_administrativa ?? '') }}')" class="btn-edit">
                                                Editar
                                            </button>
                                            <button @click="openDeleteModal({{ $dep->id }}, '{{ addslashes($dep->nombre) }}')" class="btn-delete">
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 40px; color: #6b7280;">
                                        No hay dependencias registradas
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($dependencias->hasPages())
                        <div style="margin-top: 20px;">
                            {{ $dependencias->links() }}
                        </div>
                    @endif

                    <!-- Modal Crear -->
                    <div x-show="showCreateModal" x-cloak class="modal-overlay" @click.self="showCreateModal = false">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="text-lg font-semibold">Crear Nueva Dependencia</h3>
                            </div>
                            <form method="POST" action="{{ route('dependencias.store') }}" class="modal-body">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">Nombre *</label>
                                    <input type="text" name="nombre" class="form-input" required />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Clave dependencia *</label>
                                    <input type="text" name="clave_dependencia" class="form-input" required />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Unidad administrativa</label>
                                    <input type="text" name="unidad_administrativa" class="form-input" />
                                </div>
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
                                <h3 class="text-lg font-semibold">Editar Dependencia #<span x-text="editData.id"></span></h3>
                            </div>
                            <form method="POST" :action="`/dependencias/${editData.id}`" class="modal-body">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label class="form-label">Nombre *</label>
                                    <input type="text" name="nombre" x-model="editData.nombre" class="form-input" required />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Clave dependencia *</label>
                                    <input type="text" name="clave_dependencia" x-model="editData.clave" class="form-input" required />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Unidad administrativa</label>
                                    <input type="text" name="unidad_administrativa" x-model="editData.unidad" class="form-input" />
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
                                <h3 class="text-lg font-semibold">Eliminar Dependencia #<span x-text="deleteData.id"></span></h3>
                            </div>
                            <div class="modal-body">
                                <p class="text-gray-700 mb-6">¿Está seguro de que desea eliminar la dependencia "<span x-text="deleteData.nombre" class="font-semibold"></span>"?</p>
                                <p class="text-sm text-red-600 mb-6">Esta acción no se puede deshacer.</p>
                                <form method="POST" :action="`/dependencias/${deleteData.id}`" style="display: flex; justify-content: flex-end; gap: 12px;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="showDeleteModal = false" class="btn-secondary">Cancelar</button>
                                    <button type="submit" class="btn-delete">Eliminar</button>
                                </form>
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
                const tableContainer = document.getElementById('tableContainer');
                if (tableContainer) {
                    tableContainer.classList.add('loaded');
                }
            }, 100);
        });
    </script>
</x-app-layout>