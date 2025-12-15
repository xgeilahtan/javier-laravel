@extends('layouts.app')

@section('title', 'Meus Agendamentos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/gestao-agendamentos.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        .btn-action { margin-right: 5px; }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <div class="management-card mt-4">
                <h2 class="page-title mb-4">Gestão de Agendamentos</h2>
                <div class="table-responsive">
                    <table id="tabelaAgendamentos" class="table table-striped table-hover w-100">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Profissional</th>
                                <th>Serviço</th>
                                <th>Início</th>
                                <th>Fim</th>
                                <th>Valor</th>
                                <th>Observações</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditarStatus" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alterar Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editAgendamentoId">
                    <div class="mb-3">
                        <label for="selectStatus" class="form-label">Novo status:</label>
                        <select class="form-select" id="selectStatus">
                            <option value="Confirmado">Confirmado</option>
                            <option value="Concluido">Concluído</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                        <small class="text-muted mt-2 d-block">
                            "Cancelado" libera o horário na agenda.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" onclick="salvarEdicaoStatus()">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalConfirmarCancelamento" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Cancelar Agendamento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p class="fs-5">Tem certeza que deseja cancelar este agendamento?</p>
                    <p class="text-muted small">Essa ação liberará o horário para outros clientes.</p>
                    <input type="hidden" id="cancelAgendamentoId">
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não, voltar</button>
                    <button type="button" class="btn btn-danger" onclick="executarCancelamento()">Sim, cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalFeedback" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" id="modalFeedbackHeader">
                    <h5 class="modal-title" id="modalFeedbackTitle">Mensagem</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p id="modalFeedbackMessage" class="mb-0"></p>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-sm btn-secondary w-100" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        var tabela;

        $(document).ready(function() {
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            tabela = $('#tabelaAgendamentos').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ route('api.gestao_agendamentos.data') }}", // Confirme se sua rota é essa ou api...
                    dataSrc: ""
                },
                columns: [
                    { data: 'idAgendamento' },
                    { data: 'cliente' },
                    { data: 'profissional' },
                    { data: 'nome_servico' },
                    { data: 'data_hora_inicio' },
                    { data: 'data_hora_fim' },
                    { data: 'preco' },
                    { data: 'observacoes' },
                    {
                        data: 'status',
                        render: function(data) {
                            let color = 'secondary';
                            if (data === 'Confirmado') color = 'success';
                            if (data === 'Cancelado') color = 'danger';
                            if (data === 'Concluido') color = 'primary';
                            return `<span class="badge bg-${color}">${data}</span>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let botoes = '';
                            let isCliente = (row.nivel_usuario == 3);
                            let isCancelado = (row.status === 'Cancelado');
                            let isConcluido = (row.status === 'Concluido');

                            // Forçamos a conversão para booleano para evitar erros de undefined
                            let podeCancelarPeloTempo = (row.pode_cancelar === true || row.pode_cancelar === 1);

                            // --- BOTÃO EDITAR (Não aparece para Cliente) ---
                            if (!isCliente && !isCancelado) {
                                botoes += `
                                    <button class="btn btn-sm btn-primary btn-action" onclick="abrirModalEdicao(${row.idAgendamento}, '${row.status}')">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>`;
                            }

                            // --- BOTÃO CANCELAR ---
                            // Regra: Não estar cancelado, Não estar concluído E (Se for cliente, estar no prazo)
                            // Se for Profissional/Admin, o 'podeCancelarPeloTempo' vem true do backend, então funciona.
                            if (!isCancelado && !isConcluido && podeCancelarPeloTempo) {
                                botoes += `
                                    <button class="btn btn-sm btn-danger btn-action" onclick="abrirModalConfirmacaoCancelamento(${row.idAgendamento})">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>`;
                            }

                            // --- FEEDBACKS VISUAIS ---
                            if (isConcluido) {
                                botoes += `<span class="text-success small"><i class="fas fa-check"></i> Finalizado</span>`;
                            } else if (isCancelado) {
                                botoes += `<span class="text-muted small">Cancelado</span>`;
                            } else if (isCliente && !podeCancelarPeloTempo) {
                                // Mostra ícone de cadeado se perdeu o prazo
                                botoes += `<span class="text-secondary small" title="Prazo expirado"><i class="fas fa-lock"></i> Prazo < 24h</span>`;
                            }

                            return botoes;
                        }
                    }
                ],
                language: { url: "{{ asset('js/pt-BR.json') }}" }, // Ou sua url cdn se preferir
                responsive: true,
                order: [[0, "desc"]]
            });
        });

        /* ============================================================
           FUNÇÕES DE AÇÃO (MODAIS)
           ============================================================ */

        // --- 1. FUNÇÕES PARA EDITAR (PROFISSIONAL) ---
        function abrirModalEdicao(id, statusAtual) {
            $('#editAgendamentoId').val(id);
            $('#selectStatus').val(statusAtual);
            new bootstrap.Modal(document.getElementById('modalEditarStatus')).show();
        }

        function salvarEdicaoStatus() {
            let id = $('#editAgendamentoId').val();
            let novoStatus = $('#selectStatus').val();
            enviarRequisicaoStatus(id, novoStatus, 'modalEditarStatus');
        }

        // --- 2. FUNÇÕES PARA CANCELAR (TODOS) ---
        function abrirModalConfirmacaoCancelamento(id) {
            $('#cancelAgendamentoId').val(id);
            new bootstrap.Modal(document.getElementById('modalConfirmarCancelamento')).show();
        }

        function executarCancelamento() {
            let id = $('#cancelAgendamentoId').val();
            enviarRequisicaoStatus(id, 'Cancelado', 'modalConfirmarCancelamento');
        }

        // --- 3. FUNÇÃO CENTRALIZADA DE AJAX ---
        function enviarRequisicaoStatus(id, status, modalIdParaFechar) {
            $.ajax({
                url: `/agendamentos/${id}/status`,
                type: 'POST',
                data: { status: status },
                success: function(response) {
                    if (response.success) {
                        // Fecha o modal ativo
                        bootstrap.Modal.getInstance(document.getElementById(modalIdParaFechar)).hide();

                        // Recarrega a tabela
                        tabela.ajax.reload(null, false);

                        // Mostra feedback bonito
                        exibirMensagem('Sucesso', response.message, true);
                    }
                },
                error: function(xhr) {
                    // Tenta fechar o modal mesmo com erro, ou mantenha aberto se preferir
                    bootstrap.Modal.getInstance(document.getElementById(modalIdParaFechar)).hide();

                    let msg = 'Ocorreu um erro inesperado.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    exibirMensagem('Erro', msg, false);
                }
            });
        }

        // --- 4. FUNÇÃO PARA EXIBIR MENSAGEM SEM ALERT ---
        function exibirMensagem(titulo, mensagem, sucesso) {
            let header = $('#modalFeedbackHeader');
            let title = $('#modalFeedbackTitle');

            // Muda a cor do cabeçalho baseada no sucesso/erro
            if (sucesso) {
                header.removeClass('bg-danger text-white').addClass('bg-success text-white');
            } else {
                header.removeClass('bg-success text-white').addClass('bg-danger text-white');
            }

            title.text(titulo);
            $('#modalFeedbackMessage').text(mensagem);

            new bootstrap.Modal(document.getElementById('modalFeedback')).show();
        }
    </script>
@endpush
