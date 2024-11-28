<div class="container mx-auto mt-4 px-4">
    <h2 class="text-2xl font-bold text-center mb-4">Estadístaaicas de Categorías</h2>

    <!-- Contenedor del gráfico -->
    <div class="w-full max-w-4xl mx-auto">
        <canvas id="categoryChart"></canvas>
    </div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:load', function () {
            // Verifica los datos cargados por Livewire
            console.log('Datos de categoría:', @json($categoryPercentages));

            const data = @json($categoryPercentages);  // Datos del gráfico

            const ctx = document.getElementById('categoryChart').getContext('2d');

            // Verifica que 'ctx' esté cargado
            if (!ctx) {
                console.error('No se pudo obtener el contexto del canvas');
                return;
            }

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,  // Etiquetas (categorías)
                    datasets: [{
                        label: 'Porcentaje por Categoría',
                        data: data.values,  // Valores (número de noticias o datos)
                        backgroundColor: data.colors,  // Colores para cada categoría
                        borderColor: '#000',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';  // Mostrar porcentaje en el eje Y
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush

</div>
