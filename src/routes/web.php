<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\CarrosselController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProfissionalController;
use App\Http\Controllers\AtividadeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rota temporária de debug
// Rota temporária de debug
Route::get('/debug-drive', function () {
    try {
        $startTime = microtime(true);
        $log = [];
        $log[] = "1. Iniciando teste RAW dentro do Laravel...";

        $client = new \Google\Client();

        // REPLICANDO CONFIGURAÇÃO DO STANDALONE
        $httpClient = new \GuzzleHttp\Client([
            'verify' => false,
            'force_ip_resolve' => 'v4',
            'timeout' => 20,
            'connect_timeout' => 10
        ]);
        $client->setHttpClient($httpClient);

        $credentialPath = storage_path('app/google-drive-credentials.json.json');
        if (!file_exists($credentialPath)) {
            $credentialPath = storage_path('app/google-drive-credentials.json');
        }

        $client->setAuthConfig($credentialPath);
        $client->addScope(\Google\Service\Drive::DRIVE);

        $service = new \Google\Service\Drive($client);

        $log[] = "2. Serviço criado. Tentando listar...";

        // Obter ID da pasta (mesma lógica do Provider)
        $folderId = config('filesystems.disks.google.folder');
        if (preg_match('/folders\/([a-zA-Z0-9-_]+)/', $folderId, $matches)) {
            $folderId = $matches[1];
        }
        $log[] = "   -> Filtrando pela pasta ID: " . $folderId;

        $optParams = array(
            'pageSize' => 10,
            'fields' => 'files(id, name, mimeType, webContentLink, webViewLink)',
            'q' => "'$folderId' in parents and trashed = false"
        );
        $results = $service->files->listFiles($optParams);

        $filesFound = [];
        foreach ($results->getFiles() as $file) {
            $filesFound[] = [
                'name' => $file->getName(),
                'id' => $file->getId(),
                'link' => $file->getWebContentLink()
            ];
        }

        $duration = microtime(true) - $startTime;

        return response()->json([
            'status' => 'success',
            'duration' => round($duration, 2) . 's',
            'mode' => 'RAW_CLIENT_NO_FLYSYSTEM',
            'count' => count($filesFound),
            'files' => $filesFound,
            'log' => $log
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

Route::get('/drive-img/{id}', [App\Http\Controllers\HomeController::class, 'proxyImage'])->name('drive.image');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/sobre', [App\Http\Controllers\AboutController::class, 'index'])->name('sobre');

Route::get('/contato', function () {
    return view('pages.contato');
})->name('contato');

Route::get('/servicos', [ServiceController::class, 'index'])->name('servicos');

// --- AUTENTICAÇÃO E CADASTRO ---
Route::post('/cliente/registrar', [ClienteController::class, 'store'])->name('cliente.store');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- ROTAS AUTENTICADAS (Usuários Logados) ---
Route::middleware(['auth'])->group(function () {

    // Perfil
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile.show');

    // Agendamentos (Visão do Cliente)
    Route::get('/agendamento', [AgendamentoController::class, 'create'])->name('agendamento.create');
    Route::post('/agendamento', [AgendamentoController::class, 'store'])->name('agendamento.store');

    // Atualização de Status (Geralmente usado via AJAX)
    Route::post('/agendamentos/{id}/status', [AgendamentoController::class, 'updateStatus'])->name('agendamentos.updateStatus');
});


// --- PÁGINAS DE GESTÃO (ADMINISTRATIVAS) ---
// Estas rotas retornam as VIEWS (HTML)
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/gestao_servicos', function () {
        return view('pages.gestaoServicos');
    })->name('gestao_servicos');

    Route::get('/gestao_agendamentos', function () {
        return view('pages.gestaoAgendamentos');
    })->name('gestao_agendamentos');

    // View de Atividades (Vínculos)
    Route::get('/gestao_atividades', [AtividadeController::class, 'index'])->name('gestao_atividades');

    // View de Profissionais
    Route::get('/gestao_prof', [ProfissionalController::class, 'index'])->name('gestao_prof');
});


// --- API INTERNA (RETORNA JSON) ---
// Todas as rotas aqui começam com /api/
Route::prefix('api')->middleware(['auth'])->group(function () {

    // 1. BUSCAS PARA O SELECT2 (Correção do erro de rota duplicada)
    // URLs distintas para não haver conflito
    Route::get('/profissionais/search', [ProfissionalController::class, 'search'])->name('api.profissionais.search');
    Route::get('/servicos/search', [ServiceController::class, 'search'])->name('api.servicos.search');


    // 2. API DE AGENDAMENTOS E DISPONIBILIDADE
    Route::get('/servicos/{servico}/profissionais', [AgendamentoController::class, 'getProfissionaisPorServico'])->name('api.servico.profissionais');
    Route::get('/horarios-disponiveis', [AgendamentoController::class, 'getHorariosDisponiveis'])->name('api.horarios.disponiveis');
    Route::get('/gestao_agendamentos', [AgendamentoController::class, 'data'])->name('api.gestao_agendamentos.data');


    // 3. API DE GESTÃO DE SERVIÇOS (Apenas Admin)
    Route::middleware(['admin'])->group(function () {
        Route::get('/gestao_servicos', [ServiceController::class, 'data'])->name('api.servicos.data');
        Route::post('/gestao_servicos', [ServiceController::class, 'store'])->name('api.servicos.store');
        Route::put('/gestao_servicos/{id}', [ServiceController::class, 'update'])->name('api.servicos.update'); // Corrigido falta de '/'
        Route::delete('/gestao_servicos/{id}', [ServiceController::class, 'destroy'])->name('api.servicos.destroy');
    });


    // 4. API DE GESTÃO DE ATIVIDADES / VÍNCULOS (Apenas Admin)
    Route::middleware(['admin'])->group(function () {
        Route::get('/gestao_atividades/data', [AtividadeController::class, 'data'])->name('api.atividades.data');
        Route::post('/gestao_atividades/store', [AtividadeController::class, 'store'])->name('api.atividades.store');
        Route::post('/gestao_atividades/destroy', [AtividadeController::class, 'destroy'])->name('api.atividades.destroy');
    });


    // 5. API DE GESTÃO DE PROFISSIONAIS (Apenas Admin)
    Route::prefix('gestao_prof')->middleware(['admin'])->name('api.gestao_prof.')->group(function () {
        Route::get('/data', [ProfissionalController::class, 'data'])->name('data');
        Route::post('/store', [ProfissionalController::class, 'storeApi'])->name('store.api');
        Route::put('/update/{id}', [ProfissionalController::class, 'updateApi'])->name('update.api');
        Route::delete('/destroy/{id}', [ProfissionalController::class, 'destroyApi'])->name('destroy.api');
    });

});
