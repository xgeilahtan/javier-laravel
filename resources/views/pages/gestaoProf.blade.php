@extends('layouts.app')

@section('title', 'Gestão de Profissionais')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/gestao-profissionais.css') }}">
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.min.css">

    <style>
        /* Ajuste do DataTables */
        .dataTables_scrollHeadInner { width: 100% !important; }
        .dataTables_scrollHeadInner table { width: 100% !important; }

        /* --- ESTILOS PERSONALIZADOS DOS MODAIS (IDENTIDADE VISUAL) --- */

        /* Variável de cor (Vinho) */
        :root {
            --cor-vinho-modal: var(--cor-vinho, #800020);
        }

        /* Cabeçalho Padrão do Site */
        .modal-header-custom {
            background-color: var(--cor-vinho-modal);
            color: #fff;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
            border-bottom: none;
        }

        /* Botão Fechar (X) branco */
        .modal-header-custom .close {
            color: #fff;
            text-shadow: none;
            opacity: 0.8;
        }
        .modal-header-custom .close:hover {
            opacity: 1;
        }

        /* Botão Padrão Vinho */
        .btn-vinho {
            background-color: var(--cor-vinho-modal);
            color: #fff;
            border: none;
        }
        .btn-vinho:hover {
            background-color: #5a0016; /* Um pouco mais escuro */
            color: #fff;
        }

        /* Ícones grandes no corpo do modal */
        .modal-icon-large {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }
        .text-vinho { color: var(--cor-vinho-modal); }
    </style>
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <div class="management-card">

                <h2 class="page-title">Gestão de Profissionais</h2>

                {{-- 1. TABELA DE LISTAGEM --}}
                <div class="table-responsive mb-5">
                    <table id="tabela-profissionais" class="table table-striped table-bordered w-100">
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
                                    <label for="id_prof" class="form-label">ID</label>
                                    <input type="number" class="form-control" id="id_prof" name="id_profissional" readonly placeholder="Auto">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nome_prof" class="form-label">Nome Completo</label>
                                    <input type="text" class="form-control" id="nome_prof" name="nome" placeholder="Ex: Maria Silva" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="especialidade_prof" class="form-label">Especialidade</label>
                                    <input type="text" class="form-control" id="especialidade_prof" name="especialidade" placeholder="Ex: Manicure" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cpf_prof" class="form-label">CPF</label>
                                    <input type="text" class="form-control" id="cpf_prof" name="cpf" placeholder="000.000.000-00" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="data_nascimento_prof" class="form-label">Data de Nascimento</label>
                                    <input type="date" class="form-control" id="data_nascimento_prof" name="dt_nasc" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="genero_prof" class="form-label">Gênero</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                        </div>
                                        <select class="form-select form-control" id="genero_prof" name="genero">
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
                                    <label for="telefone_prof" class="form-label">Telefone</label>
                                    <input type="tel" class="form-control" id="telefone_prof" name="telefone" placeholder="(00) 00000-0000">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email_prof" class="form-label">E-mail (Login)</label>
                                    <input type="email" class="form-control" id="email_prof" name="email" placeholder="email@exemplo.com" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password_prof" class="form-label">Senha</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password_prof" name="password" placeholder="Definir senha" required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" id="toggle-senha-prof">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BLOCO 3: ENDEREÇO --}}
                        <div class="group-title mt-4">Endereço</div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cep_prof" class="form-label">CEP</label>
                                    <input type="text" class="form-control" id="cep_prof" name="cep" placeholder="00000-000" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="endereco_prof" class="form-label">Rua / Logradouro</label>
                                    <input type="text" class="form-control" id="endereco_prof" name="endereco" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bairro_prof" class="form-label">Bairro</label>
                                    <input type="text" class="form-control" id="bairro_prof" name="bairro" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="cidade_prof" class="form-label">Cidade</label>
                                    <input type="text" class="form-control" id="cidade_prof" name="cidade" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="uf_prof" class="form-label">Estado (UF)</label>
                                    <input type="text" class="form-control" id="uf_prof" name="uf" maxlength="2" required>
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

    {{-- ================= MODAIS (Mantenha no final do content) ================= --}}

    <div class="modal fade" id="modalSucesso" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title"><i class="fas fa-check-circle"></i> Sucesso</h5>
                    {{-- Removi o botão X do header para forçar o uso do botão OK --}}
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-check-circle modal-icon-large text-vinho"></i>
                    <p class="mb-0 font-weight-bold" id="msgSucesso">Operação realizada com sucesso.</p>
                </div>
                <div class="modal-footer">
                    {{-- ADICIONADO ID: btn-fechar-sucesso --}}
                    <button type="button" class="btn btn-vinho px-4" id="btn-fechar-sucesso">OK</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalErro" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Atenção</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-times-circle modal-icon-large text-danger"></i>
                    <p class="mb-0" id="msgErro">Ocorreu um erro.</p>
                </div>
                <div class="modal-footer">
                    {{-- ADICIONADO ID: btn-fechar-erro --}}
                    <button type="button" class="btn btn-secondary px-4" id="btn-fechar-erro" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalConfirmarExclusao" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title"><i class="fas fa-trash-alt"></i> Confirmar Exclusão</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-question-circle modal-icon-large text-vinho"></i>
                    <p class="font-weight-bold">Tem certeza que deseja excluir este profissional?</p>
                    <small class="text-muted">Essa ação removerá o acesso do sistema e não pode ser desfeita.</small>
                    <input type="hidden" id="idParaExcluir">
                </div>
                <div class="modal-footer">
                    {{-- ADICIONADO ID: btn-cancelar-exclusao --}}
                    <button type="button" class="btn btn-secondary" id="btn-cancelar-exclusao" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btn-confirmar-exclusao">Sim, Excluir</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Dependências --}}
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            var form = $("#cadastro-form-prof");
            var tabela;

            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            // --- FUNÇÕES DE MENSAGEM ---
            function showSuccess(msg) {
                $('#msgSucesso').text(msg);
                $('#modalSucesso').modal('show');
            }

            function showError(msg) {
                if (typeof msg === 'string') {
                    $('#msgErro').html(msg.replace(/\n/g, "<br>"));
                } else {
                    $('#msgErro').text(msg);
                }
                $('#modalErro').modal('show');
            }

            // --- CORREÇÃO: EVENTOS MANUAIS DOS BOTÕES DOS MODAIS ---
            $('#btn-fechar-sucesso').click(function() {
                $('#modalSucesso').modal('hide');
            });

            $('#btn-fechar-erro').click(function() {
                $('#modalErro').modal('hide');
            });

            $('#btn-cancelar-exclusao').click(function() {
                $('#modalConfirmarExclusao').modal('hide');
            });
            // -------------------------------------------------------

            // 1. MÁSCARAS
            $('#cep_prof').mask('00000-000');
            $('#cpf_prof').mask('000.000.000-00', { reverse: true });

            var SPMaskBehavior = function(val) {
                return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
            };
            var spOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(SPMaskBehavior.apply({}, arguments), options);
                }
            };
            $('#telefone_prof').mask(SPMaskBehavior, spOptions);

            // 2. VIA-CEP
            $("#cep_prof").blur(function() {
                var cep = $(this).val().replace(/\D/g, '');
                if (cep != "") {
                    var validacep = /^[0-9]{8}$/;
                    if(validacep.test(cep)) {
                        $("#endereco_prof, #bairro_prof, #cidade_prof, #uf_prof").val("...");

                        $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {
                            if (!("erro" in dados)) {
                                $("#endereco_prof").val(dados.logradouro);
                                $("#bairro_prof").val(dados.bairro);
                                $("#cidade_prof").val(dados.localidade);
                                $("#uf_prof").val(dados.uf);
                            } else {
                                $("#endereco_prof, #bairro_prof, #cidade_prof, #uf_prof").val("");
                                showError("CEP não encontrado.");
                            }
                        });
                    } else {
                        showError("Formato de CEP inválido.");
                    }
                }
            });

            // 3. DATATABLE
            tabela = $('#tabela-profissionais').DataTable({
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
                language: { url: '{{ asset('js/pt-BR.json') }}' },
                responsive: true,
                scrollX: true,
                autoWidth: false
            });

            // 4. LIMPAR
            function limparFormulario() {
                form[0].reset();
                $('#id_prof').val('');
                $('#password_prof').prop('required', true);
            }
            $("#btn-limpar").click(limparFormulario);

            // 5. PREENCHER DADOS
            $('#tabela-profissionais tbody').on('click', 'tr', function() {
                var data = tabela.row(this).data();
                if (!data) return;

                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    tabela.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }

                $('#id_prof').val(data.idProfissional);
                $('#nome_prof').val(data.user.name);
                $('#genero_prof').val(data.user.genero);
                $('#data_nascimento_prof').val(data.user.dt_nasc);
                $('#telefone_prof').val(data.user.telefone).trigger('input');
                $('#email_prof').val(data.user.email);
                $('#especialidade_prof').val(data.especialidade);
                $('#cpf_prof').val(data.cpf).trigger('input');
                $('#cep_prof').val(data.user.cep).trigger('input');
                $('#endereco_prof').val(data.user.endereco);
                $('#bairro_prof').val(data.user.bairro);
                $('#cidade_prof').val(data.user.cidade);
                $('#uf_prof').val(data.user.uf);

                $('#password_prof').val('').prop('required', false);
            });

            // 6. SUBMIT
            form.submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '{{ route("api.gestao_prof.store.api") }}',
                    data: formData,
                    success: function(response) {
                        $('.modal').modal('hide');
                        showSuccess("Profissional salvo com sucesso!");
                        tabela.ajax.reload();
                        limparFormulario();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var msg = "<b>Verifique os erros:</b><br>";
                            $.each(errors, function(k, v) { msg += "&bull; " + v[0] + "<br>"; });
                            showError(msg);
                        } else {
                            showError('Ocorreu um erro ao tentar salvar.');
                        }
                    }
                });
            });

            // 7. EDITAR
            $("#btn-editar").click(function() {
                var id = $('#id_prof').val();
                if (!id) { showError("Selecione um profissional na tabela para editar."); return; }

                var url = '{{ route("api.gestao_prof.update.api", ":id") }}'.replace(':id', id);
                $.ajax({
                    type: 'PUT',
                    url: url,
                    data: form.serialize(),
                    success: function() {
                        showSuccess("Dados atualizados com sucesso!");
                        tabela.ajax.reload();
                        limparFormulario();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var msg = "<b>Verifique os erros:</b><br>";
                            $.each(errors, function(k, v) { msg += "&bull; " + v[0] + "<br>"; });
                            showError(msg);
                        } else {
                            showError("Erro ao atualizar os dados.");
                        }
                    }
                });
            });

            // 8. DELETAR (Fluxo com Modal)
            $("#btn-deletar").click(function() {
                var id = $('#id_prof').val();
                if (!id) { showError("Selecione um profissional na tabela para excluir."); return; }

                $('#idParaExcluir').val(id);
                $('#modalConfirmarExclusao').modal('show');
            });

            $("#btn-confirmar-exclusao").click(function() {
                var id = $('#idParaExcluir').val();
                var url = '{{ route("api.gestao_prof.destroy.api", ":id") }}'.replace(':id', id);

                $.ajax({
                    type: 'DELETE',
                    url: url,
                    success: function() {
                        $('#modalConfirmarExclusao').modal('hide');
                        showSuccess("Profissional excluído com sucesso!");
                        tabela.ajax.reload();
                        limparFormulario();
                    },
                    error: function() {
                        $('#modalConfirmarExclusao').modal('hide');
                        showError("Erro ao tentar excluir o profissional.");
                    }
                });
            });

            // 9. TOGGLE SENHA
            $("#toggle-senha-prof").click(function() {
                var input = $("#password_prof");
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
