@extends('layouts.app')

@section('title', 'Agendamento')

@push('styles')
    {{-- Carrega o CSS Padronizado Claro --}}
    <link rel="stylesheet" href="{{ asset('css/pages/agendamento.css') }}">
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
                <button type="submit" class="submit-button">Confirmar Agendamento</button>
            </div>
        </form>
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

    let currentDate = new Date();
    currentDate.setDate(1); 
    let selectedDate = null;
    let selectedTime = null;

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
                
                popularDropdown(profissionalSelect, profissionaisFormatados, "Selecione um profissional...", {value: 'qualquer', text: 'Qualquer Profissional Disponível'});
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