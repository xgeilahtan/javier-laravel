<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleDriveService;

class AboutController extends Controller
{
    protected $driveService;

    public function __construct(GoogleDriveService $driveService)
    {
        $this->driveService = $driveService;
    }

    public function index()
    {
        // Get About Folder ID from env
        $folderId = env('GOOGLE_DRIVE_ABOUT_ID');
        $teamFolderId = env('GOOGLE_DRIVE_TEAM_ID'); // New env for team

        $imagens = [];
        if ($folderId) {
            $imagens = $this->driveService->getImagesFromFolder($folderId, 'carrossel_about_v1');
        }

        $equipe = [];
        if ($teamFolderId) {
            $equipe = $this->driveService->getTeamFromFolder($teamFolderId, 'team_list_v1');
        }

        return view('pages.historia', compact('imagens', 'equipe'));
    }
}
