@push('styles')
    <link href="{{ asset('css/components/modal-servico.css') }}" rel="stylesheet">
@endpush

<div id="serviceModal" class="modal-overlay">
    <div class="modal-content">
        <button class="modal-close" id="btnCloseModal">&times;</button>
        
        <div class="modal-body">
            <div class="modal-gallery-container">
                <div class="swiper mySwiper2">
                    <div class="swiper-wrapper" id="swiperWrapperMain"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
                <div thumbsSlider="" class="swiper mySwiper">
                    <div class="swiper-wrapper" id="swiperWrapperThumbs"></div>
                </div>
            </div>

            <div class="modal-info">
                <h2 id="modalTitle" class="modal-title-custom">Carregando...</h2>
                <p class="modal-price-custom" id="modalPrice">R$ --</p>
                
                <div class="modal-meta">
                    <p><i class="fas fa-clock"></i> Duração: <span id="modalDuration">-- min</span></p>
                    <p><i class="fas fa-tag"></i> Categoria: <span id="modalCategory">--</span></p>
                </div>

                <div class="modal-desc-box">
                    <h4 >Sobre o serviço</h4>
                    <p id="modalDescription">Selecione um serviço.</p>
                </div>

                <a href="{{ route('agendamento.create') }}" id="btnAgendarModal" class="btn-action">
                    Agendar
                </a>
            </div>
        </div>
    </div>
</div>