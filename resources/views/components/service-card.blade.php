@props(['servico'])

@php
    $imagensUrls = $servico->imagens->map(fn($img) => asset($img->image_path));
    if($imagensUrls->isEmpty()) {
        $imagensUrls = [asset('images/noimage.jpg')];
    }
@endphp

<div class="service-card" 
     data-id="{{ $servico->id }}"
     data-name="{{ $servico->nome }}"
     data-description="{{ $servico->descricao }}"
     data-price="R$ {{ number_format($servico->preco, 2, ',', '.') }}+"
     data-duration="{{ $servico->duracao_minutos }} min"
     data-category="{{ $servico->categoria }}"
     data-professionals="{{ json_encode($servico->profissionais->pluck('id')) }}"
     data-images="{{ json_encode($imagensUrls) }}"
     >

    <div class="service-card__image">
        <img src="{{ $imagensUrls[0] }}" alt="{{ $servico->nome }}">
    </div>
    
    <div class="service-card__info">
        <h3 class="service-card__title">{{ $servico->nome }}</h3>
        <p class="service-card__price">R$ {{ number_format($servico->preco, 2, ',', '.') }}+</p>
    </div>
</div>