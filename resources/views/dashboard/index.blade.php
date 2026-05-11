@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <div class="content-wrapper">
        <div class="content-header">
            <h2 class="content-title">Dashboard</h2>
            <nav class="breadcrumb">
                <span>Dashboard</span>
            </nav>
        </div>
    </div>

    <div class="content-box">
        <div class="content-box-header">
            <h3 class="content-box-title">Página Inicial</h3>
            <div class="content-box-btn"></div>
        </div>

        <x-alert />

        <div class="flex flex-wrap justify-between gap-4 w-full">
            <div class="flex-1 min-w-[300px]">
                <canvas id="barChart" class="w-full h-72"></canvas>
            </div>
            <div class="flex-1 min-w-[300px]">
                <canvas id="lineChart" class="w-full h-72"></canvas>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('barChart');

            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($labels),
                        datasets: [{
                            label: 'Novos Cadastros',
                            data: @json($data),
                            backgroundColor: 'rgba(251, 44, 54, 0.7)',
                            borderColor: 'rgba(251, 44, 54, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        resposive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        })
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('lineChart');

            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($labels),
                        datasets: [{
                            label: 'Novos Cadastros',
                            data: @json($data),
                            backgroundColor: 'rgba(251, 44, 54, 0.2)',
                            borderColor: 'rgba(251, 44, 54, 1)',
                            border: 2,
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: 'rgba(251, 44, 54, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        resposive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        })
    </script>
@endsection
