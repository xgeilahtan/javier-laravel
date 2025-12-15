@extends('layouts.app')

@section('title', 'Gestão de Serviços')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/gestao-servicos.css') }}">
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.min.css"> --}}
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <div class="management-card">
                
                <h2 class="page-title">Cadastro de Serviços</h2>

                {{-- 1. TABELA --}}
                <div class="table-responsive mb-5">
                    <table id="tabela-servicos" class="table table-striped w-100 display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Duração (min)</th>
                                <th>Preço (R$)</th>
                                <th>Categoria</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                {{-- 2. FORMULÁRIO --}}
                <div class="form-container">
                    <h3 class="form-section-title">
                        <i class="fas fa-cut"></i> Dados do Serviço
                    </h3>

                    <form id="cadastro-form-servico">
                        
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="id_servico" class="form-label">ID</label>
                                    <input type="number" class="form-control" id="id_servico" name="id_servico" readonly placeholder="Auto">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="nome" class="form-label">Nome do Serviço</label>
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Ex: Corte Masculino Degradê" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="descricao" class="form-label">Descrição Detalhada</label>
                                    <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Ex: Lavagem inclusa, finalização com pomada..." required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="categoria" class="form-label">Categoria</label>
                                    <input type="text" class="form-control" id="categoria" name="categoria" placeholder="Ex: Cabelo, Barba, Estética" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="duracao_minutos" class="form-label">Duração (Minutos)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="far fa-clock"></i></span>
                                        <input type="number" class="form-control" id="duracao_minutos" name="duracao_minutos" placeholder="Ex: 45" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="preco" class="form-label">Preço (R$)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" step="0.01" class="form-control" id="preco" name="preco" placeholder="0.00" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <button type="submit" class="btn-custom btn-save" id="btn-cadastrar">
                                <i class="fas fa-save"></i> Salvar
                            </button>
                            <button type="button" class="btn-custom btn-edit" id="btn-editar">
                                <i class="fas fa-pen"></i> Editar Selecionado
                            </button>
                            <button type="button" class="btn-custom btn-clear" id="btn-limpar">
                                <i class="fas fa-eraser"></i> Limpar
                            </button>
                            <button type="button" class="btn-custom btn-delete" id="btn-deletar">
                                <i class="fas fa-trash"></i> Deletar
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            var form = $("#cadastro-form-servico");
            var tabela;

            // 1. Configuração CSRF
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            // 2. Inicializar DataTable
            tabela = new DataTable('#tabela-servicos', {
                ajax: '{{ route("api.servicos.index") }}', // Ajuste a rota da API se necessário
                columns: [
                    { data: 'id_servico' },
                    { data: 'nome' },
                    { data: 'descricao' },
                    { data: 'duracao_minutos' },
                    { 
                        data: 'preco',
                        render: function (data, type, row) {
                            return 'R$ ' + parseFloat(data).toFixed(2);
                        }
                    },
                    { data: 'categoria' } // Certifique-se que sua API retorna 'categoria'
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
                },
                responsive: true
            });

            // 3. Helper Limpar
            function limparFormulario() {
                form[0].reset();
                $('#id_servico').val('');
            }
            $("#btn-limpar").click(limparFormulario);

            // 4. Clique na Linha (Preencher Formulário)
            $('#tabela-servicos tbody').on('click', 'tr', function() {
                var data = tabela.row(this).data();
                if (!data) return;

                // Highlight visual
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    tabela.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }

                // Preencher campos
                $('#id_servico').val(data.id_servico);
                $('#nome').val(data.nome);
                $('#descricao').val(data.descricao);
                $('#duracao_minutos').val(data.duracao_minutos);
                $('#preco').val(data.preco);
                $('#categoria').val(data.categoria);
            });

            // 5. Cadastrar (POST)
            form.submit(function(event) {
                event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{{ route("api.servicos.store") }}', // Ajuste a rota
                    data: form.serialize(),
                    success: function() {
                        alert("Serviço cadastrado com sucesso!");
                        tabela.ajax.reload();
                        limparFormulario();
                    },
                    error: function(xhr) {
                        alert("Erro ao cadastrar serviço.");
                        console.log(xhr.responseText);
                    }
                });
            });

            // 6. Editar (PUT)
            $("#btn-editar").click(function() {
                var id = $('#id_servico').val();
                if (!id) { alert("Selecione um serviço na tabela."); return; }

                // Atenção: Ajuste a rota conforme sua API (ex: api/servicos/{id})
                var url = '{{ route("api.servicos.update", ":id") }}'.replace(':id', id);

                $.ajax({
                    type: 'PUT',
                    url: url,
                    data: form.serialize(),
                    success: function() {
                        alert("Serviço atualizado!");
                        tabela.ajax.reload();
                        limparFormulario();
                    },
                    error: function() { alert("Erro ao atualizar."); }
                });
            });

            // 7. Deletar (DELETE)
            $("#btn-deletar").click(function() {
                var id = $('#id_servico').val();
                if (!id) { alert("Selecione um serviço."); return; }

                if(confirm("Tem certeza que deseja excluir este serviço?")) {
                    var url = '{{ route("api.servicos.destroy", ":id") }}'.replace(':id', id);
                    $.ajax({
                        type: 'DELETE',
                        url: url,
                        success: function() {
                            alert("Serviço excluído!");
                            tabela.ajax.reload();
                            limparFormulario();
                        },
                        error: function() { alert("Erro ao excluir."); }
                    });
                }
            });
        });
    </script>
@endpush