@extends('layouts.app')

@section('title', 'Nossos Serviços')

@push('styles')
    <link href="{{ asset('css/servico.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endpush

@section('content')
    <section class="services-page">
        <div class="container">
            <div class="services-header text-center">
                <h1>NOSSOS SERVIÇOS</h1>
            </div>

            <div class="filter-bar">
                <div class="filter-group">
                    <label for="professional-filter">PROFISSIONAL</label>
                    <select id="professional-filter" class="filter-select">
                        <option value="todos">Todos</option>
                        @foreach ($profissionais as $profissional)
                            <option value="{{ $profissional->id }}">{{ $profissional->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label for="category-filter">CATEGORIA</label>
                    <select id="category-filter" class="filter-select">
                        <option value="todas">Todas</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria }}">{{ $categoria }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="service-grid">
                @forelse ($servicos as $servico)
                    <x-service-card :servico="$servico" />
                @empty
                    <p class="text-center w-100">Nenhum serviço encontrado com os filtros selecionados.</p>
                @endforelse
            </div>
        </div>
    </section>

    <x-modal-servico />
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('js/servico.js') }}"></script>
@endpush