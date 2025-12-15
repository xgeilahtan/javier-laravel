@extends('layouts.app')

@section('title', 'Meus Agendamentos')

@push('styles')
    {{-- Reutiliza o CSS elegante da gestão --}}
    <link rel="stylesheet" href="{{ asset('css/pages/gestao-agendamentos.css') }}">
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="container">
            
            <div class="management-card">
                
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <h2 class="page-title mb-0" style="border: none;">
                        @if(Auth::user()->id_nivel == 2)
                            <i class="fas fa-calendar-day"></i> Minha Agenda
                        @else
                            <i class="fas fa-history"></i> Meus Agendamentos
                        @endif
                    </h2>
                    
                    {{-- Botão para Novo Agendamento (só aparece para cliente) --}}
                    @if(Auth::user()->id_nivel == 3)
                        <a href="{{ route('agendamento') }}" class="btn btn-outline-secondary" style="color: var(--cor-vinho); border-color: var(--cor-vinho);">
                            <i class="fas fa-plus"></i> Novo Agendamento
                        </a>
                    @endif
                </div>

                <div class="table-responsive">
                    <table id="tabelaMeusAgendamentos" class="table table-striped w-100 display">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Horário</th>
                                
                                {{-- Se for Cliente, mostra o Profissional. Se for Profissional, mostra o Cliente --}}
                                @if(Auth::user()->id_nivel == 3)
                                    <th>Profissional</th>
                                @else
                                    <th>Cliente</th>
                                @endif

                                <th>Serviço</th>
                                <th>Preço</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- O DataTables preencherá via AJAX --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Scripts do DataTables --}}
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            
            // Define a rota da API baseada no nível do usuário
            // Você precisará criar essas rotas no Laravel:
            // - api.meus_agendamentos.cliente
            // - api.meus_agendamentos.profissional
            var urlApi = "{{ Auth::user()->id_nivel == 3 ? route('api.meus_agendamentos.cliente') : route('api.meus_agendamentos.profissional') }}";

            $('#tabelaMeusAgendamentos').DataTable({
                ajax: urlApi, 
                columns: [
                    { data: 'data_formatada' },   // Ex: 15/12/2025
                    { data: 'horario' },          // Ex: 14:00
                    
                    // Lógica condicional para coluna de nome
                    { 
                        data: null,
                        render: function(data) {
                            // Se sou cliente (nível 3), mostro o nome do profissional
                            // Se sou profissional, mostro o nome do cliente
                            return "{{ Auth::user()->id_nivel == 3 }}" == "3" 
                                ? data.profissional.user.name 
                                : data.cliente.user.name;
                        }
                    },
                    
                    { data: 'servico.nome' },
                    { 
                        data: 'servico.preco',
                        render: function(data) {
                            return 'R$ ' + parseFloat(data).toFixed(2).replace('.', ',');
                        }
                    },
                    { 
                        data: 'status',
                        render: function(data) {
                            // Renderiza badge bonitinho
                            var cor = 'secondary';
                            var texto = 'Pendente';
                            
                            if(data === 'confirmado') { cor = 'success'; texto = 'Confirmado'; }
                            else if(data === 'cancelado') { cor = 'danger'; texto = 'Cancelado'; }
                            else if(data === 'concluido') { cor = 'info'; texto = 'Concluído'; }
                            
                            // Estilo inline simples ou classes bootstrap
                            return `<span class="badge badge-${cor}" style="padding: 8px 12px; font-size: 0.85em;">${texto}</span>`;
                        }
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            // Botão de Cancelar (Só mostra se não estiver cancelado/concluído)
                            if (row.status !== 'cancelado' && row.status !== 'concluido') {
                                return `<button class="btn btn-sm btn-outline-danger btn-cancelar" data-id="${data}">
                                            <i class="fas fa-times"></i> Cancelar
                                        </button>`;
                            }
                            return '-';
                        }
                    }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
                },
                responsive: true,
                order: [[ 0, "desc" ], [ 1, "desc" ]] // Ordena por Data e Hora
            });

            // Lógica do Botão Cancelar
            $('#tabelaMeusAgendamentos').on('click', '.btn-cancelar', function() {
                var id = $(this).data('id');
                if(confirm("Deseja realmente cancelar este agendamento?")) {
                    $.ajax({
                        url: '/api/agendamentos/' + id + '/cancelar', // Ajuste sua rota
                        type: 'POST', // ou PUT
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function() {
                            alert('Agendamento cancelado.');
                            $('#tabelaMeusAgendamentos').DataTable().ajax.reload();
                        },
                        error: function() {
                            alert('Erro ao cancelar.');
                        }
                    });
                }
            });
        });
    </script>
@endpush