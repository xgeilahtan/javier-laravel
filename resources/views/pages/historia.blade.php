@extends('layouts.app')

{{-- Carrega o CSS específico desta página --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/sobre.css') }}">
    {{-- Garanta que o CSS de cards já esteja carregado no layout ou aqui, pois a equipe usa cards --}}
    <link rel="stylesheet" href="{{ asset('css/components/cards.css') }}">
@endpush

@section('content')

    {{-- Seção 1: Introdução e História --}}
    <section id="sobre-intro" class="about-section py-5">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h1 class="page-title mb-4">Sobre Nós</h1>
                    <p class="lead-text">
                        No <strong>Javier Cabelo & Estética</strong>, oferecemos uma experiência única e personalizada de
                        beleza e bem-estar.
                        Nossa missão é proporcionar um atendimento excepcional em um ambiente acolhedor e moderno no coração
                        da Vila Romana.
                    </p>
                </div>
            </div>

            <div class="row align-items-center mt-5">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="history-image-container">
                        <img src="{{ asset('img/frentesalao1.jpg') }}" class="img-fluid rounded shadow-lg"
                            alt="Frente do Salão">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="history-text ps-md-4">
                        <h2 class="section-title mb-4">Nossa História</h2>
                        <p>Fundado em 1996, o <strong>Javier Cabelo & Estética</strong> nasceu com a missão de oferecer
                            serviços de beleza de alta qualidade em um ambiente acolhedor e sofisticado.</p>
                        <p>Somos um salão familiar unissex, dedicado a proporcionar aos nossos clientes uma experiência
                            excepcional que vai além da estética, promovendo bem-estar e elevando a autoestima.</p>
                        <p>Ao longo dos anos, ampliamos nossa gama de serviços e conquistamos a confiança de um público que
                            busca não apenas beleza, mas também cuidado personalizado e profissionalismo.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Seção 2: Fotos do Ambiente --}}
    <section id="sobre-espaco" class="space-section py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center mb-5">Nosso Espaço</h2>
            <div class="row g-4 justify-content-center">
                @if(isset($imagens) && count($imagens) > 0)
                    @foreach($imagens as $imgUrl)
                        <div class="col-md-4 d-flex justify-content-center">
                            <img src="{{ $imgUrl }}" class="gallery-img img-fluid rounded" alt="Ambiente interno">
                        </div>
                    @endforeach
                @else
                    {{-- Static Fallback --}}
                    <div class="col-md-4 d-flex justify-content-center">
                        <img src="{{ asset('img/salao2.jpg') }}" class="gallery-img img-fluid rounded" alt="Ambiente interno">
                    </div>
                    <div class="col-md-4 d-flex justify-content-center">
                        <img src="{{ asset('img/salao4.jpg') }}" class="gallery-img img-fluid rounded" alt="Área de lavagem">
                    </div>
                    <div class="col-md-4 d-flex justify-content-center">
                        <img src="{{ asset('img/salao5.jpg') }}" class="gallery-img img-fluid rounded" alt="Área de espera">
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Seção 3: Equipe --}}
    <section id="sobre-equipe" class="staff-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-down" data-aos-duration="1400">Nossa Equipe</h2>
                <p class="staff-intro mx-auto" data-aos="fade-up" data-aos-duration="1400" data-aos-delay="300">
                    Com quase 30 anos de experiência, nossa equipe é composta por profissionais apaixonados e
                    atualizados com as últimas tendências.
                </p>
            </div>

            {{-- Grid da Equipe (Estilos de grid vêm do cards.css, aqui só ajustamos a margem se precisar) --}}
            <div class="staff-grid" data-aos="zoom-in" data-aos-duration="1400" data-aos-delay="400">
                @if(isset($equipe) && count($equipe) > 0)
                    @foreach($equipe as $member)
                        <x-equipe :image="$member['image']" :alt="$member['name']" :specialty="$member['specialty']"
                            :name="$member['name']" />
                    @endforeach
                @else
                    {{-- Static Fallback (Displayed if no dynamic content is found) --}}
                    <x-equipe image="{{ asset('img/janio-xavier.jpg') }}" alt="Jânio"
                        specialty="Cabeleireiro Especialista em Cortes e Colorimetria" name="Jânio" />

                    <x-equipe image="{{ asset('img/divina.jpg') }}" alt="Divina"
                        specialty="Cabeleireira, Maquiadora, Esteticista e Massagista" name="Divina" />

                    <x-equipe image="{{ asset('img/nath.jpg') }}" alt="Nathalie"
                        specialty="Cabeleireira, Designer de Cílios e Sobrancelhas" name="Nathalie" />

                    <x-equipe image="{{ asset('img/janinho.jpg') }}" alt="Jânio Jr"
                        specialty="Barbeiro Especialista em Cortes Masculinos" name="Jânio" />

                    <x-equipe image="{{ asset('img/manu.jpg') }}" alt="Manuela" specialty="Cabeleireira e Maquiadora"
                        name="Manuela" />
                @endif
            </div>
        </div>
    </section>

@endsection