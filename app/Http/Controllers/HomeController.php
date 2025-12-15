<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    // Helper privado para configurar o cliente (Evita repetição)
    private function getGoogleClient()
    {
        $client = new \Google\Client();
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

        return $client;
    }

    public function index()
    {
        // Cache for 60 minutes
        // USANDO CHAVE NOVA PARA GARANTIR LIMPEZA
        $imagens = \Cache::remember('carrossel_home_reverted', 3600, function () {
            $checkImagens = [];
            try {
                $client = $this->getGoogleClient();
                $service = new \Google\Service\Drive($client);

                // Voltar a usar config original ou env direto
                $folderId = config('filesystems.disks.google.folder');
                // Fallback caso tenha mudado .env
                if (!$folderId)
                    $folderId = env('GOOGLE_DRIVE_CARROSSEL_HOME_ID');

                if (preg_match('/folders\/([a-zA-Z0-9-_]+)/', $folderId, $matches)) {
                    $folderId = $matches[1];
                }

                if ($folderId) {
                    $optParams = array(
                        'pageSize' => 5,
                        'fields' => 'files(id, mimeType)',
                        'q' => "'$folderId' in parents and trashed = false and mimeType contains 'image/'"
                    );
                    $results = $service->files->listFiles($optParams);

                    foreach ($results->getFiles() as $file) {
                        $checkImagens[] = route('drive.image', ['id' => $file->getId()]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Erro Home Revertida: ' . $e->getMessage());
            }
            return $checkImagens;
        });

        return view('welcome', compact('imagens'));
    }

    public function proxyImage($id)
    {
        try {
            $client = $this->getGoogleClient();
            $service = new \Google\Service\Drive($client);

            $response = $service->files->get($id, ['alt' => 'media']);
            $fileInfo = $service->files->get($id, ['fields' => 'mimeType']);

            $content = $response->getBody()->getContents();
            $mime = $fileInfo->getMimeType();

            return response($content)
                ->header('Content-Type', $mime)
                ->header('Cache-Control', 'public, max-age=86400');

        } catch (\Exception $e) {
            Log::error("Erro Proxy ($id): " . $e->getMessage());
            abort(404);
        }
    }
}
