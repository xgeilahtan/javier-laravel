{{-- resources/views/components/alert-modal.blade.php --}}

<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center">
            
            {{-- ADICIONADO: Cabeçalho para o botão 'X' de fechar --}}
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                {{-- O ícone será alterado pelo JS --}}
                <i id="alertModalIcon" class="fas fa-3x mb-3"></i>
                
                <h5 class="modal-title mb-2" id="alertModalTitle"></h5>
                <p id="alertModalMessage" class="mb-2"></p>
                
                {{-- O botão "Fechar" foi REMOVIDO daqui --}}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const alertModalEl = document.getElementById('alertModal');
    if (!alertModalEl) return;

    const alertModal = new bootstrap.Modal(alertModalEl);
    const modalContent = alertModalEl.querySelector('.modal-content');
    const modalTitle = document.getElementById('alertModalTitle');
    const modalIcon = document.getElementById('alertModalIcon');
    const modalMessage = document.getElementById('alertModalMessage');

    function showAlert(type, message) {
        // Remove classes antigas de cor e ícone
        modalContent.classList.remove('modal-success', 'modal-error');
        modalIcon.classList.remove('fa-check-circle', 'fa-times-circle');

        if (type === 'success') {
            modalContent.classList.add('modal-success');
            modalIcon.classList.add('fa-check-circle'); // Ícone de Check
            modalTitle.innerText = 'Sucesso!';
        } else if (type === 'error') {
            modalContent.classList.add('modal-error');
            modalIcon.classList.add('fa-times-circle'); // Ícone de 'X' (círculo)
            modalTitle.innerText = 'Ocorreu um Erro!';
        }
        
        modalMessage.innerText = message;
        alertModal.show();
    }

    @if (session('success'))
        showAlert('success', "{{ session('success') }}");
    @elseif (session('error'))
        showAlert('error', "{{ session('error') }}");
    @endif
});
</script>
@endpush