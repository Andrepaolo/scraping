<div class="container mx-auto mt-4 px-4">
    <!-- Filtros -->
    <div class="flex mb-4 gap-4">
        <input wire:model="search" type="text" class="p-2 border rounded-lg w-1/4" placeholder="Buscar noticia...">

        <select wire:model="categoryFilter" class="p-2 border rounded-lg w-1/4" wire:change="applyFilters">
            <option value="">Filtrar por categoría</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        <select wire:model="locationFilter" class="p-2 border rounded-lg w-1/4" wire:change="applyFilters">
            <option value="">Filtrar por ubicación</option>
            @foreach($locations as $location)
                <option value="{{ $location->id }}">{{ $location->name }}</option>
            @endforeach
        </select>

        <button wire:click="applyFilters" class="bg-blue-500 text-white p-2 rounded-lg">Filtrar</button>
    </div>

    <!-- Noticias -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($news as $new)
            @php
                $categoryColor = $new->category ? $new->category->color : '#D1D5DB';
            @endphp
            <div class="bg-white rounded-lg shadow-lg overflow-hidden" style="border-left: 8px solid {{ $categoryColor }}">
                <div class="p-4">
                    <h5 class="text-xl font-semibold text-gray-800 mb-2">{{ $new->title }}</h5>
                    <p class="text-xs text-gray-400">Categoría: {{ $new->category->name ?? 'No definida' }}</p>
                    <p class="text-xs text-gray-400">Ubicación: {{ $new->location->name ?? 'No definida' }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Paginación -->
    <div class="mt-6 flex justify-center">
        {{ $news->links() }}
    </div>

    <!-- Gráfico de categorías -->
    <div class="mt-6">
        <canvas id="categoryChart" width="400" height="200"></canvas>
    </div>




</div>

