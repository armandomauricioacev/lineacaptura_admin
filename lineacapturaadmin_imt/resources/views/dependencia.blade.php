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
            min-width: 0; /* Evita overflow horizontal, ajusta al contenedor */
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
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }
        
        .custom-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #f3f4f6;
            white-space: normal; /* Permite envolver contenido */
            word-break: break-word; /* Corta palabras largas */
        }
        
        .custom-table tr:hover {
            background: #f9fafb;
        }
        
        /* Contenedor interno de la tabla: scroll horizontal táctil, sin barra interna */
        .table-wrapper {
            width: 100%;
            overflow-x: hidden; /* Evita el slidebar horizontal */
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE/Edge */
            touch-action: pan-x;
            overscroll-behavior-x: contain;
        }
        .table-wrapper::-webkit-scrollbar { display: none; }
        
        /* Barras externas superior e inferior */
        .table-scroll-top,
        .table-scroll-bottom {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            height: 12px;
            touch-action: pan-x;
        }
        .table-scroll-top { margin-bottom: 8px; }
        .table-scroll-bottom { margin-top: 8px; }
        .table-scroll-top::-webkit-scrollbar,
        .table-scroll-bottom::-webkit-scrollbar { height: 8px; }
        .table-scroll-top::-webkit-scrollbar-track,
        .table-scroll-bottom::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .table-scroll-top::-webkit-scrollbar-thumb,
        .table-scroll-bottom::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .table-scroll-top::-webkit-scrollbar-thumb:hover,
        .table-scroll-bottom::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
 
 /* Ocultar scrollbar del contenedor interno (usamos barras externas) */
 .table-wrapper { scrollbar-width: none; -ms-overflow-style: none; }
 .table-wrapper::-webkit-scrollbar { display: none; }
 
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
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        .btn-close { color: #9ca3af; background: none; border: none; cursor: pointer; padding: 4px; transition: color 0.2s; }
        .btn-close:hover { color: #6b7280; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="min-height: 600px;">
                <div class="p-6 text-gray-900 min-w-full table-container" id="tableContainer">
                    <div x-data="{
                        showEditModal: false,
                        editData: {},
                        openEditModal(id, nombre, clave, unidad) {
                            this.editData = { id, nombre, clave, unidad };
                            this.showEditModal = true;
                        }
                    }">
                    
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

                    <!-- Controles superiores removidos: sin barra de búsqueda, solo tabla -->

                    <!-- Tabla de datos -->
                    <!-- Barra superior de scroll removida -->
                    <div class="table-wrapper" x-ref="tableWrapper">
                        <table class="custom-table" x-ref="customTable">
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
                                            <!-- Botón Eliminar removido; se mantiene solo Editar -->
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

                    <!-- Modal Crear removido -->

                    <!-- Modal Editar -->
                    <div x-show="showEditModal" x-cloak class="modal-overlay" @click.self="showEditModal = false">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="text-lg font-semibold">Editar Dependencia #<span x-text="editData.id"></span></h3>
                                <button @click="showEditModal = false" class="btn-close" aria-label="Cerrar">
                                    <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
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

                    <!-- Modal Eliminar removido -->
                    
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