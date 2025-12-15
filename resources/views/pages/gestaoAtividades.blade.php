@extends('layouts.app')

@section('title', 'Gestão de Atividades')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/gestao-atividades.css') }}">
    {{-- CSS do DataTables (caso não esteja no layout global) --}}
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"> --}}
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="container">
            
            <div class="management-card">
                
                <h2 class="page-title">Gestão de Atividades</h2>

                {{-- Aviso estilizado como alerta --}}
                <div class="alert-custom">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Atenção:</strong> Esta área do sistema passará por atualizações em breve.
                </div>

                {{-- Tabela de Registros --}}
                <div class="table-responsive mb-5">
                    <table id="tabelaAtividades" class="table table-striped w-100 display">
                        <thead>
                            <tr>
                                <th>ID Prof.</th>
                                <th>Nome Profissional</th>
                                <th>ID Serv.</th>
                                <th>Nome Serviço</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Dados virão via DataTables/Backend --}}
                        </tbody>
                    </table>
                </div>

                <hr>

                {{-- Formulário de Cadastro --}}
                <div class="form-section">
                    <h3 class="form-section-title">Gerenciar Vínculo</h3>
                    
                    <form id="cadastro-form-profservico">
                        
                        <div class="row align-items-end mb-3">
                            <div class="col-md-3">
                                <label for="id_profissional_usuario" class="form-label">ID Profissional</label>
                                <input type="number" class="form-control" id="id_profissional_usuario" name="id_profissional_usuario" placeholder="Ex: 10">
                            </div>
                            <div class="col-md-9">
                                <label for="nomeProfissional" class="form-label">Nome do Profissional</label>
                                <input type="text" class="form-control" id="nomeProfissional" name="nomeProfissional" readonly placeholder="O nome aparecerá aqui...">
                            </div>
                        </div>

                        <div class="row align-items-end mb-3">
                            <div class="col-md-3">
                                <label for="id_servico" class="form-label">ID Serviço</label>
                                <input type="number" class="form-control" id="id_servico" name="id_servico" placeholder="Ex: 5">
                            </div>
                            <div class="col-md-9">
                                <label for="nomeServico" class="form-label">Nome do Serviço</label>
                                <input type="text" class="form-control" id="nomeServico" name="nomeServico" readonly placeholder="O nome aparecerá aqui...">
                            </div>
                        </div>

                        <div class="btn-action-group">
                            <button type="submit" class="btn btn-cadastrar" id="btn-cadastrar">
                                <i class="fas fa-plus-circle"></i> Vincular
                            </button>
                            <button type="button" class="btn btn-deletar" id="btn-deletar">
                                <i class="fas fa-trash-alt"></i> Remover Vínculo
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Scripts do DataTables (caso não esteja no layout global) --}}
    {{-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> --}}
    
    <script>
        $(document).ready(function() {
            // Inicialização do DataTables com Tradução
            $('#tabelaAtividades').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
                },
                responsive: true
            });

            // Lógica para preencher o nome automaticamente ao digitar o ID (Exemplo)
            // Você precisará implementar a chamada AJAX real aqui
            $('#id_profissional_usuario').on('blur', function() {
                var id = $(this).val();
                if(id) {
                    // Exemplo: Simulação de busca
                    // $('#nomeProfissional').val('Carregando...');
                    // Ajax call...
                }
            });
        });
    </script>
@endpush