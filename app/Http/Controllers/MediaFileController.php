<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Téléchargement / prévisualisation de fichiers média de la table media
 * (notamment les pièces jointes des commandes). Protégé par authentification :
 * le stockage dans /tmp d'une instance n'est pas adressable statiquement, on
 * sert donc le fichier depuis le disque « public » avec le nom d'origine.
 */
class MediaFileController extends Controller
{
    public function download(Media $media): BinaryFileResponse|StreamedResponse
    {
        return $this->stream($media, forceDownload: true);
    }

    public function show(Media $media): BinaryFileResponse|StreamedResponse
    {
        return $this->stream($media, forceDownload: false);
    }

    protected function stream(Media $media, bool $forceDownload): Response
    {
        abort_if(blank($media->path), 404, 'Fichier non disponible.');

        $disk = \Illuminate\Support\Facades\Storage::disk('public');

        abort_unless($disk->exists($media->path), 404, 'Fichier non disponible sur le stockage.');

        return $forceDownload
            ? $disk->download($media->path, $media->filename)
            : $disk->response($media->path, $media->filename, [
                'Cache-Control' => 'private, no-store',
            ]);
    }
}
