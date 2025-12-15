{{-- Adicionamos um fundo cinza claro para diferenciar da seção branca anterior --}}
<section class="py-5" style="background-color: #f7f7f7;">
    
    {{-- A CORREÇÃO PRINCIPAL: Adicionando o <div class="container"> --}}
    <div class="container">

        {{-- Padronizando o título com as fontes do seu :root --}}
        <h2 class="text-center mb-4" style="font-family: var(--fonte-principal); color: var(--cor-texto);">
            O Que Nossos Clientes Dizem
        </h2>
        
        {{-- Adicionamos .g-4 para um espaçamento consistente entre os cards --}}
        <div class="row g-4">
            
            <div class="col-md-4">
                {{-- Adicionamos .h-100 para garantir a mesma altura --}}
                <div class="card depoimento h-100">
                    <div class="card-body text-center">
                        <p>"Competência aliada à educação e cortesia."</p>
                        <footer class="blockquote-footer">Cliente no <a href="https://g.co/kgs/R3WA9Li"
                                target="_blank">Google</a>
                        </footer>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card depoimento h-100">
                    <div class="card-body text-center">
                        <p>"Melhor salão de beleza da região. Atendimento nota 10!!!"</p>
                        <footer class="blockquote-footer">Cliente no <a href="https://g.co/kgs/R3WA9Li"
                                target="_blank">Google</a>
                        </footer>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card depoimento h-100">
                    <div class="card-body text-center">
                        <p>"Excelentes profissionais!! Recomendo vivamente!!!"</p>
                        <footer class="blockquote-footer">Cliente no <a href="https://g.co/kgs/R3WA9Li"
                                target="_blank">Google</a>
                        </footer>
                    </div>
                </div>
            </div>

        </div> {{-- Fim do .row --}}
    </div> {{-- Fim do .container --}}
</section>