<div class="container">
    <!-- Gráfico por Fecha y Categoría -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Noticias por Fecha y Categoría</h5>
                    <canvas id="dateCategoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Noticias por Fecha -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Noticias por Fecha</h5>
                    <canvas id="dateChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico de Noticias por Mes -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Noticias por Mes</h5>
                    <canvas id="monthChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Distribución de Noticias por Categoría (Gráfico de Torta) -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Distribución de Noticias por Categoría</h5>
                    <canvas id="categoryDistributionChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico de Noticias por Categoría (Gráfico de Barras Horizontales) -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Noticias por Categoría</h5>
                    <canvas id="categoryBarChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Interpretación de los Datos -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Interpretación de los Datos</h5>
                    <p><strong>Noticias por Fecha y Categoría:</strong> Este gráfico de líneas muestra cómo las noticias se distribuyen entre las diferentes categorías a lo largo del tiempo. Cada línea representa una categoría específica, y se observa cómo las noticias fluctúan dependiendo de la fecha. Esto nos permite identificar tendencias o periodos con mayor actividad en alguna categoría en particular.</p>

                    <p><strong>Noticias por Fecha:</strong> El gráfico de barras muestra la cantidad de noticias publicadas en cada fecha. Podemos ver de manera clara los picos de actividad en las fechas específicas y las fluctuaciones, lo que nos da una idea de los días más activos en términos de publicación.</p>

                    <p><strong>Noticias por Mes:</strong> Este gráfico de líneas muestra la cantidad de noticias distribuidas por mes. Nos permite identificar patrones mensuales, como meses con mayor o menor publicación de noticias, lo que podría estar relacionado con eventos especiales, campañas o cambios en el interés público.</p>

                    <p><strong>Distribución de Noticias por Categoría (Gráfico de Torta):</strong> Este gráfico de torta ilustra la proporción de noticias por categoría. Cada segmento representa una categoría y su tamaño indica la cantidad relativa de noticias en comparación con las demás. Esto nos da una visión clara de qué categorías dominan en términos de cobertura.</p>

                    <p><strong>Noticias por Categoría (Gráfico de Barras Horizontales):</strong> Este gráfico de barras horizontales complementa la visualización de las noticias por categoría, pero en formato horizontal. Nos permite comparar las categorías de forma rápida, destacando las que tienen mayor cantidad de noticias de manera más visual.</p>

                    <h6 class="mt-4">Conclusión:</h6>
                    <p>En conjunto, estos gráficos ofrecen una visión integral sobre las tendencias y distribuciones de noticias. Los picos de actividad pueden señalar eventos importantes, mientras que la distribución por categorías revela el enfoque de las publicaciones. Estos datos pueden ser útiles para la toma de decisiones, el análisis de tendencias y la planificación de futuras publicaciones.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Datos de Livewire
        const dateCategoryStats = @js($newsByDateAndCategory);
        const dateStats = @js($newsByDate);
        const monthStats = @js($newsByMonth);
        const categoryStats = @js($categoryDistribution);

        // Gráfico de Noticias por Fecha y Categoría
        const dateCategoryLabels = dateCategoryStats.map(item => item.date);
        const categoryLabels = [...new Set(dateCategoryStats.map(item => item.category))];
        const categoryData = categoryLabels.map(category => {
            return dateCategoryStats.filter(item => item.category === category).map(item => item.total);
        });

        new Chart(document.getElementById('dateCategoryChart'), {
            type: 'line',
            data: {
                labels: dateCategoryLabels,
                datasets: categoryLabels.map((category, index) => ({
                    label: category,
                    data: categoryData[index],
                    fill: false,
                    borderColor: `hsl(${(index * 50) % 360}, 100%, 50%)`,
                    tension: 0.1
                }))
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    x: {
                        title: { display: true, text: 'Fecha' }
                    },
                    y: {
                        title: { display: true, text: 'Cantidad de Noticias' }
                    }
                }
            }
        });

        // Gráfico de Noticias por Fecha
        const dateLabels = dateStats.map(item => item.date);
        const dateData = dateStats.map(item => item.total);

        new Chart(document.getElementById('dateChart'), {
            type: 'bar',
            data: {
                labels: dateLabels,
                datasets: [{
                    label: 'Noticias por Fecha',
                    data: dateData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true,
                        title: { display: true, text: 'Fecha' }
                    },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Cantidad de Noticias' }
                    }
                }
            }
        });

        // Gráfico de Noticias por Mes
        const monthLabels = monthStats.map(item => `${item.year}-${item.month}`);
        const monthData = monthStats.map(item => item.total);

        new Chart(document.getElementById('monthChart'), {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Noticias por Mes',
                    data: monthData,
                    fill: false,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: { display: true, text: 'Mes' }
                    },
                    y: {
                        title: { display: true, text: 'Cantidad de Noticias' }
                    }
                }
            }
        });

        // Gráfico de Distribución de Noticias por Categoría (Gráfico de Torta)
        const categoryLabelsForPie = categoryStats.map(item => item.category);
        const categoryDataForPie = categoryStats.map(item => item.total);

        new Chart(document.getElementById('categoryDistributionChart'), {
            type: 'pie',
            data: {
                labels: categoryLabelsForPie,
                datasets: [{
                    data: categoryDataForPie,
                    backgroundColor: categoryLabelsForPie.map((_, index) => `hsl(${(index * 50) % 360}, 100%, 50%)`),
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const category = tooltipItem.label;
                                const value = tooltipItem.raw;
                                return `${category}: ${value} noticias`;
                            }
                        }
                    }
                }
            }
        });

        // Gráfico de Noticias por Categoría (Gráfico de Barras Horizontales)
        const categoryLabelsForBar = categoryStats.map(item => item.category);
        const categoryDataForBar = categoryStats.map(item => item.total);

        new Chart(document.getElementById('categoryBarChart'), {
            type: 'bar',
            data: {
                labels: categoryLabelsForBar,
                datasets: [{
                    label: 'Noticias por Categoría',
                    data: categoryDataForBar,
                    backgroundColor: categoryLabelsForBar.map((_, index) => `hsl(${(index * 50) % 360}, 100%, 50%)`),
                    borderColor: 'rgba(0, 0, 0, 0.1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        title: { display: true, text: 'Cantidad de Noticias' }
                    },
                    y: {
                        title: { display: true, text: 'Categoría' }
                    }
                }
            }
        });
    });
</script>
@endpush
