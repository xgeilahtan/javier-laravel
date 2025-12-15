<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Storage;
use Google\Client;
use Google\Service\Drive;
use Masbug\Flysystem\GoogleDriveAdapter;
use League\Flysystem\Filesystem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            Storage::extend('google', function ($app, $config) {
                $client = new Client();
                // FIX: Disable SSL verification and FORCE IPv4 to avoid timeouts/hanging
                $client->setHttpClient(new \GuzzleHttp\Client([
                    'verify' => false,
                    'force_ip_resolve' => 'v4', // Force IPv4 to fix hanging on some Windows setups
                    'timeout' => 45, // Increased timeout to handle slow initial connection
                ]));

                // Prioritize Service Account JSON (User has this file)
                // We check for both options just in case
                $jsonFiles = [
                    storage_path('app/google-drive-credentials.json.json'),
                    storage_path('app/google-drive-credentials.json'),
                    $config['applicationCredentials'] ?? ''
                ];

                $authConfigured = false;
                foreach ($jsonFiles as $file) {
                    if ($file && file_exists($file)) {
                        $client->setAuthConfig($file);
                        $authConfigured = true;
                        break;
                    }
                }

                if ($authConfigured) {
                    $client->addScope(Drive::DRIVE);
                }

                if (!$authConfigured) {
                    // Fallback to OAuth keys from .env
                    if (!empty($config['clientId']) && !empty($config['clientSecret']) && !empty($config['refreshToken'])) {
                        $client->setClientId($config['clientId']);
                        $client->setClientSecret($config['clientSecret']);
                        $client->refreshToken($config['refreshToken']);
                    } else {
                        // If we are running in console and just installing, don't crash hard, but this will fail if used.
                    }
                }

                $service = new Drive($client);

                // Sanitizar o Folder ID caso o usuário tenha colado a URL completa
                $folderId = $config['folder'] ?? '/';
                if (preg_match('/folders\/([a-zA-Z0-9-_]+)/', $folderId, $matches)) {
                    $folderId = $matches[1];
                }

                $adapter = new GoogleDriveAdapter($service, $folderId);
                return new Filesystem($adapter);
            });
        } catch (\Exception $e) {
            // Log::error("Failed to register google drive disk: " . $e->getMessage());
        }
    }
}
