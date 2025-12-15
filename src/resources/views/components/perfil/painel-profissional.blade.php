@props(['user'])

{{-- 1. Detalhes do Profissional (Padronizado) --}}
<div class="profile-info-row">
    <strong>Telefone:</strong>
    <span>{{ $user->telefone ?? 'Não informado' }}</span>
</div>

<div class="profile-info-row">
    <strong>Especialidade:</strong>
    <span>{{ $user->profissional->especialidade ?? 'Não informada' }}</span>
</div>

{{-- 2. Botões de Ação --}}
<div class="admin-buttons">
    {{-- O CSS .admin-buttons já aplica o grid e o espaçamento superior --}}
    <a href="{{ route('gestao_agendamentos') }}" class="btn">
        <i class="fas fa-calendar-day"></i> <br>
        Minha Agenda
    </a>
</div>
