@extends('layouts.app')

@section('title', 'Meu Perfil')

@push('styles')
    {{-- Carrega o CSS específico do Perfil --}}
    <link rel="stylesheet" href="{{ asset('css/pages/perfil.css') }}">
@endpush

@section('content')

    <div class="profile-container">
        <div class="profile-header">
            <h1>Olá, {{ Auth::user()->name }}</h1>
            <p>Gerencie suas informações e acessos aqui.</p>
        </div>
        
        <div class="profile-details">
            
            <div class="profile-info-row">
                <strong>Email Cadastrado:</strong> 
                <span>{{ Auth::user()->email }}</span>
            </div>
            
            <div class="mt-4">
                @if(Auth::user()->id_nivel == 3)
                    {{-- Cliente --}}
                    <x-perfil.painel-cliente :user="Auth::user()" />
                    
                @elseif(Auth::user()->id_nivel == 2)
                    {{-- Profissional --}}
                    <x-perfil.painel-profissional :user="Auth::user()" />
                    
                @elseif(Auth::user()->id_nivel == 1)
                    {{-- Administrador --}}
                    <x-perfil.painel-admin />
                @endif
            </div>
        </div>

        <div id="logout-button-container">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Sair da Conta
                </button>
            </form>
        </div>
    </div>

@endsection