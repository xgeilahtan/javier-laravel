@extends('layouts.app')

@section('title', 'Gestão de Profissionais')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/gestao-profissionais.css') }}">
    {{-- DataTables CSS --}}
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.min.css"> --}}
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <div class="management-card">
                
                <h2 class="page-title">Gestão de Profissionais</h2>

                {{-- 1. TABELA DE LISTAGEM --}}
                <div class="table-responsive mb-5">
                    <table id="tabela-profissionais" class="table table-striped w-100 display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Gênero</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Especialidade</th>
                                <th>CPF</th>
                                <th>Dt. Nasc.</th>
                                <th>CEP</th>
                                <th>Endereço</th>
                                <th>Bairro</th>
                                <th>Cidade</th>
                                <th>UF</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                {{-- 2. FORMULÁRIO DE CADASTRO/EDIÇÃO --}}
                <div class="form-container">
                    <h3 class="form-section-title">
                        <i class="fas fa-user-edit"></i> Dados do Profissional
                    </h3>

                    <form id="cadastro-form-prof">
                        
                        {{-- BLOCO 1: IDENTIFICAÇÃO --}}
                        <div class="group-title">Dados Pessoais</div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="id_profissional" class="form-label">ID</label>
                                    <input type="number" class="form-control" id="id_profissional" name="id_profissional" readonly placeholder="Auto">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nome" class="form-label">Nome Completo</label>
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Ex: Maria Silva" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="especialidade" class="form-label">Especialidade</label>
                                    <input type="text" class="form-control" id="especialidade" name="especialidade" placeholder="Ex: Manicure" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cpf" class="form-label">CPF</label>
                                    <input type="text" class="form-control" id="cpf" name="cpf" placeholder="000.000.000-00" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="data-nascimento" class="form-label">Data de Nascimento</label>
                                    <input type="date" name="dt_nasc" class="form-control" id="data-nascimento" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="genero" class="form-label">Gênero</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                        <select class="form-select" id="genero" name="genero">
                                            <option value="" disabled selected>Selecione</option>
                                            <option value="F">Feminino</option>
                                            <option value="M">Masculino</option>
                                            <option value="O">Outro</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BLOCO 2: CONTATO E ACESSO --}}
                        <div class="group-title mt-4">Contato & Acesso</div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="tel" class="form-control" id="telefone" name="telefone" placeholder="(00) 00000-0000">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email" class="form-label">E-mail (Login)</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="email@exemplo.com" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="senha" class="form-label">Senha</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Definir senha" required>
                                        <button type="button" class="btn btn-outline-secondary" id="toggle-senha">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BLOCO 3: ENDEREÇO --}}
                        <div class="group-title mt-4">Endereço</div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cep" class="form-label">CEP</label>
                                    <input type="text" class="form-control" id="cep" name="cep" placeholder="00000-000" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="endereco" class="form-label">Rua / Logradouro</label>
                                    <input type="text" class="form-control" id="endereco" name="endereco" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bairro" class="form-label">Bairro</label>
                                    <input type="text" class="form-control" id="bairro" name="bairro" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="cidade" class="form-label">Cidade</label>
                                    <input type="text" class="form-control" id="cidade" name="cidade" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="uf" class="form-label">Estado (UF)</label>
                                    <input type="text" class="form-control" id="uf" name="uf" maxlength="2" required>
                                </div>
                            </div>
                        </div>

                        {{-- BOTÕES DE AÇÃO --}}
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
    {{-- Mantenha seus scripts de bibliotecas aqui --}}
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Define o formulário principal
            var form = $("#cadastro-form-prof");
            var tabela; // Declara a variável tabela fora para ser acessível globalmente

            // 1. CONFIGURAÇÃO AJAX/CSRF
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // 2. MÁSCARAS
            $('#cep').mask('00000-000');
            $('#cpf').mask('000.000.000-00', { reverse: true });
            var SPMaskBehavior = function(val) {
                return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
            };
            var spOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(SPMaskBehavior.apply({}, arguments), options);
                }
            };
            $('#telefone').mask(SPMaskBehavior, spOptions);

            // 3. VIA-CEP
            $("#cep").blur(function() {
                var cep = $(this).val().replace(/\D/g, '');
                if (cep != "") {
                    var validacep = /^[0-9]{8}$/;
                    if(validacep.test(cep)) {
                        $("#endereco").val("...");
                        $("#bairro").val("...");
                        $("#cidade").val("...");
                        $("#uf").val("...");

                        $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {
                            if (!("erro" in dados)) {
                                $("#endereco").val(dados.logradouro);
                                $("#bairro").val(dados.bairro);
                                $("#cidade").val(dados.localidade);
                                $("#uf").val(dados.uf);
                            } else {
                                alert("CEP não encontrado.");
                                $("#endereco, #bairro, #cidade, #uf").val("");
                            }
                        });
                    }
                }
            });

            // 4. DATATABLE
            tabela = new DataTable('#tabela-profissionais', {
                ajax: '{{ route("api.gestao_prof.data") }}',
                columns: [
                    { data: 'idProfissional' },
                    { data: 'user.name' },
                    { data: 'user.genero' },
                    { data: 'user.email' },
                    { data: 'user.telefone' },
                    { data: 'especialidade' },
                    { data: 'cpf' },
                    { data: 'user.dt_nasc' },
                    { data: 'user.cep' },
                    { data: 'user.endereco' },
                    { data: 'user.bairro' },
                    { data: 'user.cidade' },
                    { data: 'user.uf' }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
                },
                responsive: true,
                scrollX: true // Importante para tabelas com muitas colunas
            });

            // 5. HELPER: LIMPAR
            function limparFormulario() {
                form[0].reset();
                $('#id_profissional').val('');
                $('#password').prop('required', true); // Senha volta a ser obrigatória no cadastro
            }
            $("#btn-limpar").click(limparFormulario);

            // 6. POPULAR FORMULÁRIO AO CLICAR NA TABELA
            $('#tabela-profissionais tbody').on('click', 'tr', function() {
                var data = tabela.row(this).data();
                if (!data) return;

                // Destaca linha selecionada (opcional)
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    tabela.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }

                // Preenche campos
                $('#id_profissional').val(data.idProfissional);
                $('#nome').val(data.user.name);
                $('#genero').val(data.user.genero);
                $('#data-nascimento').val(data.user.dt_nasc);
                $('#telefone').val(data.user.telefone).trigger('input');
                $('#email').val(data.user.email);
                $('#especialidade').val(data.especialidade);
                $('#cpf').val(data.cpf).trigger('input');
                $('#cep').val(data.user.cep).trigger('input');
                $('#endereco').val(data.user.endereco);
                $('#bairro').val(data.user.bairro);
                $('#cidade').val(data.user.cidade);
                $('#uf').val(data.user.uf);

                // Senha não é obrigatória na edição
                $('#password').val('').prop('required', false);
            });

            // 7. CADASTRAR (SUBMIT)
            form.submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                
                $.ajax({
                    type: 'POST',
                    url: '{{ route("api.gestao_prof.store.api") }}',
                    data: formData,
                    success: function(response) {
                        alert("Profissional salvo com sucesso!");
                        tabela.ajax.reload();
                        limparFormulario();
                    },
                    error: function(xhr) {
                        // Tratamento de erros (mantido sua lógica original)
                        if (xhr.status === 419) alert("Sessão expirada.");
                        else if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var msg = "Erros:\n";
                            $.each(errors, function(k, v) { msg += "- " + v[0] + "\n"; });
                            alert(msg);
                        } else alert('Erro ao salvar.');
                    }
                });
            });

            // 8. EDITAR
            $("#btn-editar").click(function() {
                var id = $('#id_profissional').val();
                if (!id) { alert("Selecione um profissional na tabela."); return; }
                
                var url = '{{ route("api.gestao_prof.update.api", ":id") }}'.replace(':id', id);
                
                $.ajax({
                    type: 'PUT',
                    url: url,
                    data: form.serialize(),
                    success: function() {
                        alert("Atualizado com sucesso!");
                        tabela.ajax.reload();
                        limparFormulario();
                    },
                    error: function(xhr) { alert("Erro ao atualizar."); }
                });
            });

            // 9. DELETAR
            $("#btn-deletar").click(function() {
                var id = $('#id_profissional').val();
                if (!id) { alert("Selecione um profissional."); return; }
                
                if(confirm("Tem certeza que deseja excluir?")) {
                    var url = '{{ route("api.gestao_prof.destroy.api", ":id") }}'.replace(':id', id);
                    $.ajax({
                        type: 'DELETE',
                        url: url,
                        success: function() {
                            alert("Excluído com sucesso!");
                            tabela.ajax.reload();
                            limparFormulario();
                        },
                        error: function() { alert("Erro ao excluir."); }
                    });
                }
            });

            // 10. TOGGLE SENHA
            $("#toggle-senha").click(function() {
                var input = $("#password");
                var icon = $(this).find("i");
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                    icon.removeClass("fa-eye").addClass("fa-eye-slash");
                } else {
                    input.attr("type", "password");
                    icon.removeClass("fa-eye-slash").addClass("fa-eye");
                }
            });
        });
    </script>
@endpush