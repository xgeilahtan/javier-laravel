@props(['user'])

{{-- 1. Detalhes do Cliente (Padronizado com perfil.css) --}}
<div class="profile-info-row">
    <strong>Telefone:</strong>
    <span>{{ $user->telefone ?? 'Não informado' }}</span>
</div>

<div class="profile-info-row">
    <strong>Observações:</strong>
    <span>{{ $user->cliente->observacoes ?? 'Nenhuma informada' }}</span>
</div>

{{-- 2. Botões de Ação --}}
{{-- A classe .admin-buttons já cuida do espaçamento e do grid no CSS --}}
<div class="admin-buttons">
    {{-- Mantive a rota comentada como você mandou --}}
    <a href="{{-- route('client.appointments') --}}" class="btn">
        <i class="fas fa-calendar-alt"></i> <br>
        Meus Agendamentos
    </a>
</div>
