

<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="loginForm" method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <input type="hidden" name="form_type" value="login">
                    <div class="mb-3">
                        <label for="login-email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="login-email" name="email" value="{{ old('email') }}" placeholder="seu@email.com">
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="login-password" class="form-label">Senha</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="login-password" name="password" placeholder="Sua senha">
                            <span class="input-group-text toggle-password"><i class="fas fa-eye-slash"></i></span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-2">Entrar</button>
                </form>
                <p class="text-center mt-3 mb-0">
                    Não tem uma conta? <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">Cadastre-se</a>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Crie sua Conta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="registerForm" method="POST" action="{{ route('cliente.store') }}" enctype="multipart/form-data">
                    @csrf
                     <input type="hidden" name="form_type" value="register">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nome Completo</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Seu nome completo">
                            </div>
                            @error('name') <div class="invalid-feedback d-block mt-n2 mb-3">{{ $message }}</div> @enderror

                            <label for="email" class="form-label">Email</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Seu melhor email">
                            </div>
                             @error('email') <div class="invalid-feedback d-block mt-n2 mb-3">{{ $message }}</div> @enderror

                            <label for="telefone" class="form-label">Telefone</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="text" class="form-control @error('telefone') is-invalid @enderror" id="telefone" name="telefone" value="{{ old('telefone') }}" placeholder="(00) 00000-0000">
                            </div>
                            @error('telefone') <div class="invalid-feedback d-block mt-n2 mb-3">{{ $message }}</div> @enderror
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="dt_nasc" class="form-label">Nascimento</label>
                                    <div class="input-group mb-3">
                                         <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="date" class="form-control @error('dt_nasc') is-invalid @enderror" id="dt_nasc" name="dt_nasc" value="{{ old('dt_nasc') }}">
                                    </div>
                                    @error('dt_nasc') <div class="invalid-feedback d-block mt-n2 mb-3">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="genero" class="form-label">Gênero</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                        <select class="form-select @error('genero') is-invalid @enderror" id="genero" name="genero">
                                            <option value="" disabled {{ old('genero') ? '' : 'selected' }}>Selecione</option>
                                            <option value="F" {{ old('genero') == 'F' ? 'selected' : '' }}>Feminino</option>
                                            <option value="M" {{ old('genero') == 'M' ? 'selected' : '' }}>Masculino</option>
                                            <option value="O" {{ old('genero') == 'O' ? 'selected' : '' }}>Outro</option>
                                        </select>
                                    </div>
                                    @error('genero') <div class="invalid-feedback d-block mt-n2 mb-3">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <label for="password" class="form-label">Senha</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Mínimo 8 caracteres">
                                <span class="input-group-text toggle-password"><i class="fas fa-eye-slash"></i></span>
                            </div>
                            @error('password') <div class="invalid-feedback d-block mt-n2 mb-3">{{ $message }}</div> @enderror

                            <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Repita a senha">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="cep" class="form-label">CEP</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                                <input type="text" class="form-control @error('cep') is-invalid @enderror" id="cep" name="cep" value="{{ old('cep') }}" placeholder="00000-000">
                                <div id="cep-loader" class="input-group-text" style="display: none;">
                                    <div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">...</span></div>
                                </div>
                            </div>
                             @error('cep') <div class="invalid-feedback d-block mt-n2 mb-3">{{ $message }}</div> @enderror

                            <label for="endereco" class="form-label">Endereço</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-road"></i></span>
                                <input type="text" class="form-control @error('endereco') is-invalid @enderror" id="endereco" name="endereco" value="{{ old('endereco') }}" placeholder="Rua, Av, etc.">
                            </div>
                            @error('endereco') <div class="invalid-feedback d-block mt-n2 mb-3">{{ $message }}</div> @enderror
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="bairro" class="form-label">Bairro</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control @error('bairro') is-invalid @enderror" id="bairro" name="bairro" value="{{ old('bairro') }}">
                                    </div>
                                    @error('bairro') <div class="invalid-feedback d-block mt-n2 mb-3">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-sm-6">
                                     <label for="cidade" class="form-label">Cidade</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                                        <input type="text" class="form-control @error('cidade') is-invalid @enderror" id="cidade" name="cidade" value="{{ old('cidade') }}">
                                    </div>
                                    @error('cidade') <div class="invalid-feedback d-block mt-n2 mb-3">{{ $message }}</div> @enderror
                                </div>
                            </div>

                             <label for="uf" class="form-label">Estado (UF)</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-flag"></i></span>
                                <input type="text" class="form-control @error('uf') is-invalid @enderror" id="uf" name="uf" value="{{ old('uf') }}">
                            </div>
                            @error('uf') <div class="invalid-feedback d-block mt-n2 mb-3">{{ $message }}</div> @enderror

                             <label for="foto" class="form-label">Foto de Perfil (Opcional)</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-camera"></i></span>
                                <input class="form-control @error('foto') is-invalid @enderror" type="file" id="foto" name="foto">
                            </div>
                             @error('foto') <div class="invalid-feedback d-block mt-n2 mb-3">{{ $message }}</div> @enderror

                             <label for="observacoes" class="form-label">Observações (Alergias, etc.)</label>
                             <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-pen-alt"></i></span>
                                <textarea class="form-control" id="observacoes" name="observacoes" rows="2">{{ old('observacoes') }}</textarea>
                             </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Finalizar Cadastro</button>
                </form>
                 <p class="text-center mt-3 mb-0">
                    Já possui uma conta? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Faça Login</a>
                </p>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // --- REABRIR MODAL COM ERROS ---
    @if ($errors->any())
        let myModal;
        @if (old('form_type') === 'register' || $errors->has('name') || $errors->has('telefone') || $errors->has('foto'))
            myModal = new bootstrap.Modal(document.getElementById('registerModal'));
        @else
            myModal = new bootstrap.Modal(document.getElementById('loginModal'));
        @endif
        myModal.show();
    @endif

    // --- MÁSCARAS (visual apenas) ---
    function applyMask(input, maskFn) {
        input.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = maskFn(value);
        });
    }

    // Máscara de CEP: 00000-000
    const cepInput = document.getElementById('cep');
    if (cepInput) {
        applyMask(cepInput, function (v) {
            return v.replace(/(\d{5})(\d)/, '$1-$2').substring(0, 9);
        });
    }

    // Máscara de Telefone: (00) 00000-0000
    const telInput = document.getElementById('telefone');
    if (telInput) {
        applyMask(telInput, function (v) {
            if (v.length <= 10) {
                return v.replace(/(\d{2})(\d{0,4})(\d{0,4})/, '($1) $2-$3');
            } else {
                return v.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
            }
        });
    }

    // --- REMOVER MÁSCARAS ANTES DO ENVIO ---
    ['loginForm', 'registerForm'].forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function () {
                // Remove máscara do CEP
                const cep = document.getElementById('cep');
                if (cep) cep.value = cep.value.replace(/\D/g, '');

                // Remove máscara do telefone
                const tel = document.getElementById('telefone');
                if (tel) tel.value = tel.value.replace(/\D/g, '');
            });
        }
    });

    // --- VALIDAÇÃO DE SENHA (registro) ---
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');
    const passwordError = document.createElement('div');
    passwordError.className = 'password-match-error';
    passwordError.textContent = 'As senhas não coincidem.';
    if (passwordConfirm && password) {
        passwordConfirm.parentNode.insertBefore(passwordError, passwordConfirm.nextSibling);

        function validatePasswordMatch() {
            if (passwordConfirm.value && password.value !== passwordConfirm.value) {
                passwordConfirm.classList.add('is-invalid');
                passwordError.style.display = 'block';
            } else {
                passwordConfirm.classList.remove('is-invalid');
                passwordError.style.display = 'none';
            }
        }

        password.addEventListener('input', validatePasswordMatch);
        passwordConfirm.addEventListener('input', validatePasswordMatch);
    }

    // --- TOGGLE DE SENHA ---
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', function () {
            const input = this.previousElementSibling;
            const type = input.type === 'password' ? 'text' : 'password';
            input.type = type;
            const iconEl = this.querySelector('i');
            iconEl.classList.toggle('fa-eye');
            iconEl.classList.toggle('fa-eye-slash');
        });
    });

    // --- BUSCA DE CEP ---
    if (cepInput) {
        const loader = document.getElementById('cep-loader');
        const fields = {
            endereco: document.getElementById('endereco'),
            bairro: document.getElementById('bairro'),
            cidade: document.getElementById('cidade'),
            uf: document.getElementById('uf'),
        };

        cepInput.addEventListener('blur', function () {
            const cep = this.value.replace(/\D/g, '');
            if (cep.length !== 8) {
                Object.values(fields).forEach(f => f.disabled = false);
                return;
            }

            // Desabilita campos e mostra loader
            Object.values(fields).forEach(f => {
                if (f) f.disabled = true;
            });
            if (loader) loader.style.display = 'flex';

            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        if (fields.endereco) fields.endereco.value = data.logradouro || '';
                        if (fields.bairro) fields.bairro.value = data.bairro || '';
                        if (fields.cidade) fields.cidade.value = data.localidade || '';
                        if (fields.uf) fields.uf.value = data.uf || '';
                    } else {
                        alert('CEP não encontrado.');
                        Object.values(fields).forEach(f => {
                            if (f) f.value = '';
                        });
                    }
                })
                .catch(() => {
                    alert('Erro ao buscar o CEP. Verifique sua conexão.');
                })
                .finally(() => {
                    Object.values(fields).forEach(f => {
                        if (f) f.disabled = false;
                    });
                    if (loader) loader.style.display = 'none';
                });
        });
    }
});
</script>
@endpush