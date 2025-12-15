<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\CarrosselController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProfissionalController;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/sobre', function () {
    return view('pages.historia');
})->name('sobre');

Route::get('/contato', function () {
    return view('pages.contato');
})->name('contato');

//Rotas temporárias
Route::get('/gestao_servicos', function () {
    return view('pages.gestaoServicos');
})->name('gestao_servicos')->middleware(['auth', 'admin']);

Route::get('/gestao_agendamentos', function () {
    return view('pages.gestaoAgendamentos');
})->name('gestao_agendamentos')->middleware(['auth']);

Route::get('/gestao_atividades', function () {
    return view('pages.gestaoAtividades');
})->name('gestao_atividades')->middleware(['auth', 'admin']);
//Fim das rotas temporárias

Route::get('/servicos', [ServiceController::class, 'index'])->name('servicos');

// Adicione esta rota para a sua página de perfil
Route::get('/perfil', [ProfileController::class, 'show'])
    ->name('profile.show')
    ->middleware('auth');

Route::post('/cliente/registrar', [ClienteController::class, 'store'])->name('cliente.store');

Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/agendamento', [AgendamentoController::class, 'create'])->name('agendamento.create');
Route::post('/agendamento', [AgendamentoController::class, 'store'])->name('agendamento.store');

// --- API INTERNA PARA O FRONT-END ---
// Este bloco é o que cria as URLs que começam com /api/
Route::prefix('api')->group(function () {
    // Esta linha cria a rota: GET /api/servicos/{servico}/profissionais
    Route::get('/servicos/{servico}/profissionais', [AgendamentoController::class, 'getProfissionaisPorServico'])->name('api.servico.profissionais');

    // Esta linha cria a rota: GET /api/horarios-disponiveis
    Route::get('/horarios-disponiveis', [AgendamentoController::class, 'getHorariosDisponiveis'])->name('api.horarios.disponiveis');
    Route::get('/gestao_agendamentos', [AgendamentoController::class, 'data'])->name('api.gestao_agendamentos.data');
});


Route::get('/gestao_prof', [ProfissionalController::class, 'index'])
    ->name('gestao_prof')
    ->middleware(['auth', 'admin']);


Route::middleware(['auth', 'admin'])->prefix('api/gestao_prof')->name('api.gestao_prof.')->group(function () {
    // Rota para alimentar o DataTable (AJAX)
    // URL final: /api/gestao_prof/data
    Route::get('/data', [ProfissionalController::class, 'data'])->name('data');

    // Rota para Criar (AJAX)
    // URL final: /api/gestao_prof/store
    Route::post('/store', [ProfissionalController::class, 'storeApi'])->name('store.api');

    // Rota para Atualizar (AJAX)
    // URL final: /api/gestao_prof/update/123
    Route::put('/update/{id}', [ProfissionalController::class, 'updateApi'])->name('update.api');

    // Rota para Deletar (AJAX)
    // URL final: /api/gestao_prof/destroy/123
    Route::delete('/destroy/{id}', [ProfissionalController::class, 'destroyApi'])->name('destroy.api');
});
