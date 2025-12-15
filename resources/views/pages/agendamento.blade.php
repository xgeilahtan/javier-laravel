@extends('layouts.app')

@section('title', 'Agendamento')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/agendamento.css') }}">
    {{-- CSS Inline para o Modal (Você pode mover para o agendamento.css depois) --}}
    <style>
        /* Fundo escuro atrás do modal */
        .modal-overlay {
            display: none; /* Oculto por padrão */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        /* A caixa do modal */
        .modal-box {
            background-color: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 400px;
            text-align: center;
            animation: fadeIn 0.3s ease;
        }

        .modal-box h3 {
            color: var(--cor-vinho, #800020); /* Fallback caso a variável não exista */
            margin-bottom: 1rem;
        }

        .modal-box p {
            font-size: 1rem;
            color: #333;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .modal-actions {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .btn-modal {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            flex: 1;
            transition: background 0.2s;
        }

        .btn-cancelar {
            background-color: #e0e0e0;
            color: #333;
        }

        .btn-confirmar {
            background-color: var(--cor-vinho, #800020);
            color: #fff;
        }

        .btn-confirmar:hover {
            opacity: 0.9;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endpush

@section('content')
    <div class="scheduler-container">
        <div class="scheduler-header">
            <h1>Agende seu Horário</h1>
            <p>Simples, rápido e online. Garanta seu momento de cuidado.</p>
        </div>

        <form id="agendamento-form" method="POST" action="{{ route('agendamento.store') }}">
            @csrf

            {{-- Campos Ocultos para envio --}}
            <input type="hidden" name="servico_id" id="hidden_servico_id">
            <input type="hidden" name="profissional_id" id="hidden_profissional_id">
            <input type="hidden" name="data" id="hidden_data">
            <input type="hidden" name="horario" id="hidden_horario">

            <div class="step" id="step1">
                <h2>1. O que você deseja agendar?</h2>

                <div class="form-group" id="servico-group">
                    <label for="servico">Selecione o Serviço:</label>
                    <select id="servico" name="servico_select_display">
                        <option value="">Clique para escolher...</option>
                        @foreach ($servicos as $servico)
                            <option value="{{ $servico->id_servico }}">{{ $servico->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group form-section-disabled" id="profissional-group">
                    <label for="profissional">Prefere algum Profissional?</label>
                    <select id="profissional" name="profissional_select_display">
                        <option value="">Aguardando seleção do serviço...</option>
                    </select>
                </div>
            </div>

            <div class="step form-section-disabled" id="step2">
                <h2>2. Escolha o melhor dia</h2>
                <div class="date-time-container">

                    <div class="calendar-container">
                        <div class="calendar">
                            <div class="calendar-header">
                                <button type="button" id="prev-month-btn">
                                    <i class="fas fa-chevron-left">&lt;</i>
                                </button>
                                <span id="calendar-month-year"></span>
                                <button type="button" id="next-month-btn">
                                    <i class="fas fa-chevron-right">&gt;</i>
                                </button>
                            </div>
                            <div class="calendar-days" id="calendar-days"></div>
                        </div>
                    </div>

                    <div class="available-times">
                        <label class="mb-3 d-block text-center">Horários Disponíveis:</label>
                        <div class="times-grid" id="times-grid"></div>
                        <div id="no-times-message" class="no-times-message" style="display:none;">
                            Selecione um dia no calendário para ver os horários.
                        </div>
                    </div>
                </div>
            </div>

            <div class="step form-section-disabled" id="step3">
                <h2>3. Confira e Confirme</h2>
                <div class="summary-box">
                    <h3>Resumo do Agendamento</h3>
                    <p><strong>Serviço:</strong> <span id="summary-servico">-</span></p>
                    <p><strong>Profissional:</strong> <span id="summary-profissional">-</span></p>
                    <p><strong>Data:</strong> <span id="summary-data">-</span></p>
                    <p><strong>Horário:</strong> <span id="summary-horario">-</span></p>
                    <hr>
                    <p style="font-size: 1.2rem; color: var(--cor-vinho);">
                        <strong>Total:</strong> <span id="summary-preco">R$ 0,00</span>
                    </p>
                </div>
                {{-- Mudei o type para "button" para não enviar o form direto --}}
                <button type="button" class="submit-button" id="btn-pre-confirmar">Confirmar Agendamento</button>
            </div>
        </form>
    </div>

    {{-- ESTRUTURA DO MODAL --}}
    <div id="modal-aviso" class="modal-overlay">
        <div class="modal-box">
            <h3><i class="fas fa-exclamation-circle"></i> Atenção</h3>
            <p>
                Lembre-se que o cancelamento só pode ser realizado com até <strong>24 horas de antecedência</strong> do horário marcado.
            </p>
            <div class="modal-actions">
                <button type="button" class="btn-modal btn-cancelar" id="modal-btn-voltar">Voltar</button>
                <button type="button" class="btn-modal btn-confirmar" id="modal-btn-confirmar">Estou ciente, Agendar</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const todosServicos = @json($servicos);

    const servicoSelect = document.getElementById('servico');
    const profissionalSelect = document.getElementById('profissional');
    const profissionalGroup = document.getElementById('profissional-group');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    const calendarHeader = document.getElementById('calendar-month-year');
    const calendarDaysContainer = document.getElementById('calendar-days');
    const prevMonthBtn = document.getElementById('prev-month-btn');
    const nextMonthBtn = document.getElementById('next-month-btn');
    const timesGrid = document.getElementById('times-grid');
    const noTimesMessage = document.getElementById('no-times-message');

    // Elementos do Modal
    const btnPreConfirmar = document.getElementById('btn-pre-confirmar');
    const modalAviso = document.getElementById('modal-aviso');
    const btnModalVoltar = document.getElementById('modal-btn-voltar');
    const btnModalConfirmar = document.getElementById('modal-btn-confirmar');
    const formAgendamento = document.getElementById('agendamento-form');

    let currentDate = new Date();
    currentDate.setDate(1);
    let selectedDate = null;
    let selectedTime = null;

    // --- LÓGICA DO MODAL ---

    // 1. Ao clicar no botão principal de confirmar
    btnPreConfirmar.addEventListener('click', function() {
        // Validação simples para garantir que tudo foi preenchido
        if(!document.getElementById('hidden_servico_id').value ||
           !document.getElementById('hidden_data').value ||
           !document.getElementById('hidden_horario').value) {
            alert('Por favor, selecione todas as opções antes de confirmar.');
            return;
        }

        // Abre o modal
        modalAviso.style.display = 'flex';
    });

    // 2. Botão Voltar (Fecha o modal)
    btnModalVoltar.addEventListener('click', function() {
        modalAviso.style.display = 'none';
    });

    // 3. Botão Confirmar final (Envia o formulário)
    btnModalConfirmar.addEventListener('click', function() {
        formAgendamento.submit();
    });

    // 4. Fechar modal clicando fora (opcional)
    modalAviso.addEventListener('click', function(e) {
        if (e.target === modalAviso) {
            modalAviso.style.display = 'none';
        }
    });

    // --- FIM LÓGICA DO MODAL ---


    // --- 1. Seleção de Serviço ---
    servicoSelect.addEventListener('change', async function () {
        const servicoId = this.value;
        resetarSelecao(profissionalSelect);
        esconderPassosSeguintes();

        if (servicoId) {
            profissionalSelect.innerHTML = '<option>Carregando profissionais...</option>';
            profissionalGroup.classList.remove('form-section-disabled');
            try {
                // Ajuste a rota da API conforme necessário
                const response = await fetch(`/api/servicos/${servicoId}/profissionais`);
                if (!response.ok) throw new Error('Erro ao buscar profissionais.');
                const profissionais = await response.json();

                // Mapeia para o formato dropdown
                const profissionaisFormatados = profissionais.map(p => ({ id: p.idProfissional, nome: p.user.name }));

                popularDropdown(profissionalSelect, profissionaisFormatados, "Selecione um profissional...");
            } catch (error) {
                console.error('Erro:', error);
                profissionalSelect.innerHTML = '<option>Erro ao carregar</option>';
            }
        } else {
            profissionalGroup.classList.add('form-section-disabled');
        }
    });

    // --- 2. Seleção de Profissional ---
    profissionalSelect.addEventListener('change', function() {
        const profissionalId = this.value;
        if (profissionalId) { // Aceita 'qualquer' ou ID específico
            step2.classList.remove('form-section-disabled');
            renderizarCalendario();
            noTimesMessage.style.display = 'block'; // Mostra msg inicial
            noTimesMessage.textContent = "Selecione um dia para ver horários.";
        } else {
            esconderPassosSeguintes();
        }
    });

    // --- Navegação do Calendário ---
    prevMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderizarCalendario();
    });

    nextMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderizarCalendario();
    });

    // --- Funções Auxiliares ---
    function popularDropdown(selectElement, items, placeholder, defaultOption = null) {
        selectElement.innerHTML = '';
        if (placeholder) selectElement.add(new Option(placeholder, ''));
        if (defaultOption) selectElement.add(new Option(defaultOption.text, defaultOption.value));
        items.forEach(item => selectElement.add(new Option(item.nome, item.id)));
    }

    function resetarSelecao(selectElement) { selectElement.innerHTML = ''; }

    function esconderPassosSeguintes() {
        step2.classList.add('form-section-disabled');
        step3.classList.add('form-section-disabled');
        timesGrid.innerHTML = '';
        noTimesMessage.style.display = 'none';
        selectedDate = null;
        selectedTime = null;
        const diaSelecionado = document.querySelector('.day.selected');
        if (diaSelecionado) diaSelecionado.classList.remove('selected');
    }

    function renderizarCalendario() {
        calendarDaysContainer.innerHTML = '';
        const month = currentDate.getMonth();
        const year = currentDate.getFullYear();

        // Capitaliza o mês (ex: "Dezembro 2025")
        const nomeMes = currentDate.toLocaleString('pt-BR', { month: 'long' });
        calendarHeader.textContent = `${nomeMes.charAt(0).toUpperCase() + nomeMes.slice(1)} ${year}`;

        // Cabeçalho Dias da Semana
        const weekdays = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
        weekdays.forEach(day => {
            const dayEl = document.createElement('div');
            dayEl.classList.add('weekday');
            dayEl.textContent = day;
            calendarDaysContainer.appendChild(dayEl);
        });

        const firstDayOfMonth = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        // Dias Vazios antes do dia 1
        for (let i = 0; i < firstDayOfMonth; i++) {
            const emptyCell = document.createElement('div');
            calendarDaysContainer.appendChild(emptyCell);
        }

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Renderiza os dias
        for (let i = 1; i <= daysInMonth; i++) {
            const dayEl = document.createElement('div');
            dayEl.classList.add('day');
            dayEl.textContent = i;

            const dayDate = new Date(year, month, i);
            dayDate.setHours(0, 0, 0, 0);

            // Bloqueia Domingo(0), Segunda(1) e Passado
            if (dayDate.getDay() === 0 || dayDate.getDay() === 1 || dayDate < today) {
                dayEl.classList.add('disabled');
            } else {
                dayEl.addEventListener('click', () => {
                    // Remove seleção anterior
                    const anterior = document.querySelector('.day.selected');
                    if (anterior) anterior.classList.remove('selected');

                    dayEl.classList.add('selected');
                    selectedDate = dayDate;
                    buscarHorarios(dayDate);
                });
            }

            // Mantém seleção visual ao mudar mês
            if (selectedDate && dayDate.getTime() === selectedDate.getTime()) {
                dayEl.classList.add('selected');
            }

            calendarDaysContainer.appendChild(dayEl);
        }
    }

    async function buscarHorarios(date) {
        const servicoId = servicoSelect.value;
        const profissionalId = profissionalSelect.value;
        if (!servicoId || !profissionalId || !date) return;

        timesGrid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: #666;">Buscando horários...</p>';
        noTimesMessage.style.display = 'none';
        step3.classList.add('form-section-disabled');

        try {
            const dataFormatada = date.toISOString().split('T')[0];
            const url = `/api/horarios-disponiveis?data=${dataFormatada}&profissional_id=${profissionalId}&servico_id=${servicoId}`;

            const response = await fetch(url);
            if (!response.ok) throw new Error('Erro na API.');

            const horariosDisponiveis = await response.json();

            timesGrid.innerHTML = '';

            if (horariosDisponiveis.length > 0) {
                noTimesMessage.style.display = 'none';
                horariosDisponiveis.forEach(time => {
                    const timeSlot = document.createElement('div');
                    timeSlot.classList.add('time-slot');
                    timeSlot.textContent = time;

                    timeSlot.addEventListener('click', () => {
                        const slotAnterior = document.querySelector('.time-slot.selected');
                        if (slotAnterior) slotAnterior.classList.remove('selected');

                        timeSlot.classList.add('selected');
                        selectedTime = time;

                        preencherResumoEFormulario();
                        step3.classList.remove('form-section-disabled');

                        // Scroll suave até o resumo
                        step3.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    });

                    timesGrid.appendChild(timeSlot);
                });
            } else {
                timesGrid.innerHTML = '';
                noTimesMessage.style.display = 'block';
                noTimesMessage.textContent = 'Sem horários livres nesta data.';
            }
        } catch (error) {
            console.error(error);
            timesGrid.innerHTML = '<p style="grid-column: 1/-1; color: var(--cor-vinho);">Erro ao carregar horários.</p>';
        }
    }

    function preencherResumoEFormulario() {
        if (!selectedDate || !selectedTime) return;

        const servicoOption = servicoSelect.options[servicoSelect.selectedIndex];
        const profissionalOption = profissionalSelect.options[profissionalSelect.selectedIndex];

        // Encontra dados completos do serviço (incluindo preço)
        const servicoInfo = todosServicos.find(s => s.id_servico == servicoOption.value);

        // Preenche Texto
        document.getElementById('summary-servico').textContent = servicoOption.text;
        document.getElementById('summary-profissional').textContent = profissionalOption.text;

        // Formata Data (ex: "sexta-feira, 15 de dezembro de 2025")
        const dataLegivel = selectedDate.toLocaleDateString('pt-BR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        document.getElementById('summary-data').textContent = dataLegivel;

        document.getElementById('summary-horario').textContent = selectedTime;

        if(servicoInfo) {
            const precoFormatado = parseFloat(servicoInfo.preco).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            document.getElementById('summary-preco').textContent = precoFormatado;
            document.getElementById('hidden_servico_id').value = servicoInfo.id_servico;
        }

        // Preenche Inputs Ocultos
        document.getElementById('hidden_profissional_id').value = profissionalOption.value;
        document.getElementById('hidden_data').value = selectedDate.toISOString().split('T')[0];
        document.getElementById('hidden_horario').value = selectedTime;
    }
});
</script>
@endpush
