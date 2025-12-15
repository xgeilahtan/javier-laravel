@extends('layouts.app')

@section('title', 'Meus Agendamentos')

@push('styles')
    {{-- CSS Específico desta página --}}
    <link rel="stylesheet" href="{{ asset('css/pages/gestao-agendamentos.css') }}">
    
    {{-- Se você não estiver carregando o CSS do DataTables no layout principal, descomente abaixo: --}}
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"> --}}
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="container">
            
            <div class="management-card">
                <h2 class="page-title">Gestão de Agendamentos</h2>
                
                <div class="table-responsive">
                    <table id="tabelaAgendamentos" class="table table-striped w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Profissional</th>
                                <th>Serviço</th>
                                <th>Início</th>
                                <th>Fim</th>
                                <th>Status</th> {{-- Adicionei Status como sugestão --}}
                                <th>Ações</th>  {{-- Coluna para Editar/Cancelar --}}
                            </tr>
                        </thead>
                        <tbody>
                            {{-- O DataTables preencherá aqui via AJAX ou Loop do Laravel --}}
                            {{-- Exemplo estático para visualização: --}}
                            <tr>
                                <td>#1023</td>
                                <td>Maria Eduarda</td>
                                <td>Jânio Xavier</td>
                                <td>Corte Feminino</td>
                                <td>15/12/2025 14:00</td>
                                <td>15/12/2025 15:00</td>
                                <td><span class="badge-status status-confirmado">Confirmado</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary">Ver</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    {{-- Scripts do DataTables (Se já não estiverem no layout) --}}
    {{-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script> --}}

    <script>
        $(document).ready(function() {
            $('#tabelaAgendamentos').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json" // Tradução para PT-BR
                },
                responsive: true,
                order: [[ 0, "desc" ]] // Ordena pelo ID decrescente
            });
        });
    </script>
@endpush
