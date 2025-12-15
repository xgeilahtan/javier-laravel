<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GoogleDriveService
{
    private function getClient()
    {
        $client = new Client();
        $httpClient = new GuzzleClient([
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
        $client->addScope(Drive::DRIVE); // Readonly is sufficient usually, but we stick to what works

        return $client;
    }

    /**
     * Fetch images from a specific Google Drive folder.
     * 
     * @param string $folderIdEnvKey The .env key name for the folder ID (e.g., 'GOOGLE_DRIVE_HOME_ID')
     * @param string $cacheKey Unique key for caching results
     * @return array List of proxy URLs
     */
    public function getImagesFromFolder(string $folderId, string $cacheKey)
    {
        // Cache for 60 minutes
        return Cache::remember($cacheKey, 3600, function () use ($folderId, $cacheKey) {
            $images = [];
            try {
                $client = $this->getClient();
                $service = new Drive($client);

                // Extract ID if full URL provided
                if (preg_match('/folders\/([a-zA-Z0-9-_]+)/', $folderId, $matches)) {
                    $folderId = $matches[1];
                }

                if ($folderId) {
                    $optParams = array(
                        'pageSize' => 5, // Limit for performance
                        'fields' => 'files(id, mimeType)',
                        'q' => "'$folderId' in parents and trashed = false and mimeType contains 'image/'"
                    );
                    $results = $service->files->listFiles($optParams);

                    foreach ($results->getFiles() as $file) {
                        // Generate Proxy URL
                        $images[] = route('drive.image', ['id' => $file->getId()]);
                    }
                }
            } catch (\Exception $e) {
                Log::error("GoogleDriveService Error ($cacheKey): " . $e->getMessage());
            }
            return $images;
        });
    }

    /**
     * Fetch team members from a specific Google Drive folder.
     * Filenames are parsed to extract Name and Specialty (optional).
     * Format: "Name - Specialty.jpg" or just "Name.jpg"
     * 
     * @param string $folderIdEnvKey The .env key name for the folder ID
     * @param string $cacheKey Unique key for caching results
     * @return array List of team members ['name', 'specialty', 'image']
     */
    public function getTeamFromFolder(string $folderId, string $cacheKey)
    {
        // Cache for 60 minutes
        return Cache::remember($cacheKey, 3600, function () use ($folderId, $cacheKey) {
            $team = [];
            try {
                $client = $this->getClient();
                $service = new Drive($client);

                // Extract ID if full URL provided
                if (preg_match('/folders\/([a-zA-Z0-9-_]+)/', $folderId, $matches)) {
                    $folderId = $matches[1];
                }

                if ($folderId) {
                    $optParams = array(
                        'pageSize' => 50, // Larger limit for team
                        'fields' => 'files(id, name, mimeType)',
                        'q' => "'$folderId' in parents and trashed = false and mimeType contains 'image/'"
                    );
                    $results = $service->files->listFiles($optParams);

                    foreach ($results->getFiles() as $file) {
                        $filename = pathinfo($file->getName(), PATHINFO_FILENAME);

                        // Default values
                        $name = $filename;
                        $specialty = 'Especialista'; // Default specialty

                        // Try to parse "Name - Specialty"
                        if (strpos($filename, '-') !== false) {
                            $parts = explode('-', $filename, 2);
                            $name = trim($parts[0]);
                            $specialty = trim($parts[1]);
                        }

                        $team[] = [
                            'name' => $name,
                            'specialty' => $specialty,
                            'image' => route('drive.image', ['id' => $file->getId()]),
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::error("GoogleDriveService Team Error ($cacheKey): " . $e->getMessage());
            }
            return $team;
        });
    }

    /**
     * Download file content for proxying.
     */
    public function getFileContent($fileId)
    {
        try {
            $client = $this->getClient();
            $service = new Drive($client);

            $response = $service->files->get($fileId, ['alt' => 'media']);
            $fileInfo = $service->files->get($fileId, ['fields' => 'mimeType']);

            return [
                'content' => $response->getBody()->getContents(),
                'mimeType' => $fileInfo->getMimeType()
            ];
        } catch (\Exception $e) {
            Log::error("GoogleDriveService Proxy Error ($fileId): " . $e->getMessage());
            return null;
        }
    }
}
