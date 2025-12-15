@extends('layouts.app')

@section('title', 'Gestão de Atividades')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/gestao-atividades.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <div class="management-card">
                <h2 class="page-title">Gestão de Atividades</h2>

                {{-- Tabela de Registros --}}
                <div class="table-responsive mb-5">
                    <table id="tabelaAtividades" class="table table-striped w-100 display">
                        <thead>
                            <tr>
                                <th>Profissional</th>
                                <th>Serviço</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                    </table>
                </div>

                <hr>

                {{-- Formulário de Cadastro --}}
                <div class="form-section">
                    <h3 class="form-section-title">Novo Vínculo</h3>
                    <form id="cadastro-form-profservico">
                        <div class="row align-items-end mb-3">
                            <div class="col-md-12">
                                <label for="id_profissional_usuario" class="form-label">Profissional</label>
                                <select class="form-select" id="id_profissional_usuario" name="id_profissional_usuario">
                                    <option value="">Pesquise um profissional...</option>
                                    {{-- Opções carregadas via AJAX --}}
                                </select>
                            </div>
                        </div>

                        <div class="row align-items-end mb-3">
                            <div class="col-md-12">
                                <label for="id_servico" class="form-label">Serviço</label>
                                <select class="form-select" id="id_servico" name="id_servico">
                                    <option value="">Pesquise um serviço...</option>
                                    {{-- Opções carregadas via AJAX --}}
                                </select>
                            </div>
                        </div>

                        <div class="btn-action-group">
                            <button type="submit" class="btn btn-cadastrar" id="btn-cadastrar">
                                <i class="fas fa-plus-circle"></i> Vincular
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Configuração Global do CSRF Token para AJAX
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            // 1. Inicialização do DataTables
            var tabela = $('#tabelaAtividades').DataTable({
                ajax: '{{ route("api.atividades.data") }}', // Rota criada no passo 2
                columns: [
                    { data: 'nome_profissional' },
                    { data: 'nome_servico' },
                    {
                        data: null,
                        render: function(data, type, row) {
                            // Botão de excluir passando os IDs escondidos
                            return `<button class="btn btn-sm btn-danger btn-desvincular"
                                        data-prof="${row.idProfissional}"
                                        data-serv="${row.id_servico}">
                                        <i class="fas fa-trash"></i>
                                    </button>`;
                        }
                    }
                ],
                language: { url: "{{ asset('js/pt-BR.json') }}" },
                responsive: true,
                autoWidth: false
            });

            // 2. Inicializa Select2 - Profissionais
            $('#id_profissional_usuario').select2({
                theme: 'bootstrap-5',
                placeholder: 'Pesquise pelo nome do profissional...',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: "{{ route('api.profissionais.search') }}", // Busca criada na resposta anterior
                    dataType: 'json',
                    data: function(params) { return { q: params.term }; },
                    processResults: function(data) { return { results: data }; }
                }
            });

            // 3. Inicializa Select2 - Serviços
            $('#id_servico').select2({
                theme: 'bootstrap-5',
                placeholder: 'Pesquise pelo nome do serviço...',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: "{{ route('api.servicos.search') }}", // Busca criada na resposta anterior
                    dataType: 'json',
                    data: function(params) { return { q: params.term }; },
                    processResults: function(data) { return { results: data }; }
                }
            });

            // 4. Submit do Formulário (Vincular)
            $('#cadastro-form-profservico').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('api.atividades.store') }}",
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        alert(response.message);
                        tabela.ajax.reload(); // Recarrega a tabela
                        // Limpa os selects
                        $('#id_profissional_usuario').val(null).trigger('change');
                        $('#id_servico').val(null).trigger('change');
                    },
                    error: function(xhr) {
                        alert('Erro: ' + (xhr.responseJSON.message || 'Erro desconhecido'));
                    }
                });
            });

            // 5. Clique no botão de Desvincular (da tabela)
            $('#tabelaAtividades').on('click', '.btn-desvincular', function() {
                if(!confirm("Deseja realmente remover este vínculo?")) return;

                var profId = $(this).data('prof');
                var servId = $(this).data('serv');

                $.ajax({
                    url: "{{ route('api.atividades.destroy') }}",
                    method: "POST",
                    data: { id_profissional: profId, id_servico: servId },
                    success: function(response) {
                        alert(response.message);
                        tabela.ajax.reload();
                    },
                    error: function() {
                        alert('Erro ao desvincular.');
                    }
                });
            });
        });
    </script>
@endpush
