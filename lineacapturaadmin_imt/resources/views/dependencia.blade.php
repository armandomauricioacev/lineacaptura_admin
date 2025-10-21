<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dependencia') }}
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 table-container" id="tableContainer">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clave dependencia</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unidad administrativa</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Creado</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actualizado</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($dependencias as $dep)
                                <tr x-data="{ openEdit: false, openDelete: false }">
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $dep->id }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $dep->nombre }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $dep->clave_dependencia }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $dep->unidad_administrativa }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ optional($dep->created_at)->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ optional($dep->updated_at)->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-2 text-sm text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button @click="openEdit = true" class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white text-xs font-medium rounded hover:bg-indigo-700 transition-colors duration-200">
                                                Editar
                                            </button>
                                            <button @click="openDelete = true" class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700 transition-colors duration-200">
                                                Eliminar
                                            </button>
                                        </div>

                                        <!-- Modal Editar -->
                                        <div x-show="openEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
                                            <div @click="openEdit = false" class="absolute inset-0 bg-black bg-opacity-50"></div>
                                            <div class="bg-white w-full max-w-lg rounded shadow-lg z-10">
                                                <div class="px-6 py-4 border-b">
                                                    <h3 class="text-lg font-semibold">Editar dependencia #{{ $dep->id }}</h3>
                                                </div>
                                                <form method="POST" action="{{ route('dependencias.update', $dep) }}" class="px-6 py-4">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-4">
                                                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                                        <input type="text" name="nombre" value="{{ $dep->nombre }}" class="mt-1 block w-full rounded border-gray-300" required />
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="block text-sm font-medium text-gray-700">Clave dependencia</label>
                                                        <input type="text" name="clave_dependencia" value="{{ $dep->clave_dependencia }}" class="mt-1 block w-full rounded border-gray-300" required />
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="block text-sm font-medium text-gray-700">Unidad administrativa</label>
                                                        <input type="text" name="unidad_administrativa" value="{{ $dep->unidad_administrativa }}" class="mt-1 block w-full rounded border-gray-300" />
                                                    </div>
                                                    <div class="flex justify-end gap-2">
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
                                                    <h3 class="text-lg font-semibold">Eliminar dependencia #{{ $dep->id }}</h3>
                                                </div>
                                                <div class="px-6 py-4">
                                                    <p class="text-sm text-gray-700">¿Confirma eliminar la dependencia "{{ $dep->nombre }}"?</p>
                                                </div>
                                                <form method="POST" action="{{ route('dependencias.destroy', $dep) }}" class="px-6 pb-4 flex justify-end gap-2">
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
                                    <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">No hay registros de dependencias.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $dependencias->links() }}
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