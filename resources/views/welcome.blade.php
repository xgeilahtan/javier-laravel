@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/cards.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/welcome.css') }}">
@endpush

@section('title', 'Javier Cabelo e Estética - Salão de Beleza e Estética')

@section('content')

    {{-- 1. NOVA SEÇÃO HERO (COM O CARROSSEL DENTRO) --}}
    <section class="hero-com-carrossel">
        
        {{-- O SEU CARROSSEL (intacto) --}}
        <x-carrossel/>

        {{-- O texto de boas-vindas (agora por cima) --}}
        {{-- 2. SEÇÃO DE BOAS-VINDAS (CTA) --}}
{{-- MUDANÇA: Adicionamos um fundo cinza claro para destacar a seção --}}
<section class="py-5 text-center" style="background-color: #f0f0f0;">
    
    {{-- Adicionamos o <div class="container"> aqui --}}
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                
                {{-- MUDANÇA: Título agora usa a cor de destaque --}}
                <h1 style="font-family: var(--fonte-principal); color: var(--cor-vinho);">
                    Javier Cabelo e Estética
                </h1>
                
                {{-- MUDANÇA: Texto corrigido para "SP" --}}
                <p class="lead" style="font-family: var(--fonte-secundaria); color: #555;">
                    Seu novo espaço de beleza em SP. Cada detalhe pensado para seu bem-estar.
                </p>
                
                {{-- O seu botão .btn-agendar que já existe --}}
                <a href="{{ route('agendamento.create') }}" class="btn-agendar">
                    Agendar Horário
                </a> 
            </div>
        </div>
    </div>
</section>

    </section>

    {{-- 2. SEÇÃO DE DEPOIMENTOS (AVALIAÇÃO) --}}
    {{-- Esta seção permanece exatamente como estava --}}
    <x-avaliacao/>

@endsection