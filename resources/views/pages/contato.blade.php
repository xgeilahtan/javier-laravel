@extends('layouts.app')

@push('styles')
    {{-- Carrega o CSS específico desta página --}}
    <link rel="stylesheet" href="{{ asset('css/pages/contato.css') }}">
@endpush

@section('content')
    <section id="localizacao" class="contact-section py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5">Localização & Contato</h2>
            
            <div class="row g-4 align-items-center">
                <div class="col-lg-5 col-md-6">
                    <div class="contact-card">
                        <h3 class="card-subtitle mb-3">Venha nos visitar</h3>
                        <p class="mb-4 text-muted">
                            Estamos no coração da Vila Romana, em São Paulo. 
                            Nosso espaço foi projetado para oferecer conforto e bem-estar durante sua visita.
                        </p>

                        <ul class="contact-list">
                            <li class="contact-item">
                                <span class="icon-label">📍 Endereço:</span>
                                <a href="https://maps.google.com/?q=Rua+Tito,+1725+-+Vila+Romana,+São+Paulo+-+SP" 
                                   target="_blank" 
                                   class="address-link">
                                   Rua Tito, 1725 - Vila Romana, São Paulo - SP
                                </a>
                            </li>

                            <li class="contact-item">
                                <span class="icon-label">📞 Telefone:</span>
                                <span class="contact-value">(11) 3641-9604</span>
                            </li>

                            <li class="contact-item">
                                <span class="icon-label">📱 WhatsApp:</span>
                                <a href="https://wa.me/551138639629" target="_blank" class="whatsapp-link">
                                    (11) 3863-9629
                                </a>
                            </li>

                            <li class="contact-item">
                                <span class="icon-label">✉️ E-mail:</span>
                                <span class="contact-value">javiercabeloeestetica@gmail.com</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-7 col-md-6">
                    <div class="map-container">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3657.947234698874!2d-46.69476832375685!3d-23.53439466060411!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94cef876ca803b9d%3A0x6b29094073541450!2sR.%20Tito%2C%201725%20-%20Vila%20Romana%2C%20S%C3%A3o%20Paulo%20-%20SP%2C%2005051-001!5e0!3m2!1spt-BR!2sbr!4v1700000000000!5m2!1spt-BR!2sbr" 
                            width="100%" 
                            height="450" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection