<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_YouTube;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\FavVideo;

class apiController extends Controller
{
    public function searchInApi(Request $request)
{
    
    $search = $request->validate([
        'search' => ['required'],
    ]);

    // Hier kannst du die API-Codes tauschen
    // $apiKey = 'AIzaSyC57jVf-kqK_LUtKPVIBn9ITX_fuTQtt14';
    $apiKey = 'AIzaSyCoEWhLPxeFGbE-pSI3ve8TWW7g0EOwVDk';

    $url = 'https://www.googleapis.com/youtube/v3/search';
    $params = [
        'part' => 'snippet,id',
        'q' => $search['search'],
        'key' => $apiKey,
        'maxResults' => 10,
    ];

    $response = file_get_contents($url . '?' . http_build_query($params));
    $result = json_decode($response, true);

    // Filtere die Videos nach gültiger ID
    $filteredResults = [];
    if (isset($result['items'])) {
        foreach ($result['items'] as $item) {
            if (isset($item['id']['videoId'])) {
                $filteredResults[] = $item;
            }
        }
    }
    
    // Ersetze das ursprüngliche Ergebnis mit den gefilterten Ergebnissen
    
    $result['items'] = $filteredResults;

    // Gib das Ergebnis als JSON zurück
    return response()->json($result);
}

    public function index(Request $request)
    {
        try {
            $userId = Auth::user()->id;
            $videoId = $request->input('ID');

            // Überprüfen, ob das Favoritenvideo bereits existiert
            $existingFavVideo = FavVideo::where('user_id', $userId)->where('video_id', $videoId)->first();

            if ($existingFavVideo) {
                return response()->json(['message' => 'Favoritenvideo bereits vorhanden']);
            }

            // Neues Favoritenvideo erstellen und speichern
            $favVideo = new FavVideo();
            $favVideo->user_id = $userId;
            $favVideo->video_id = $videoId;
            $favVideo->titel = $request->input('Title');
            $favVideo->thumbnail = $request->input('Thumbnail');
            $favVideo->save();

            return response()->json(['message' => 'Erfolgreich favorisiert']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
    public function getFavVideos()
    {
        // Prüfen, ob der Benutzer eingeloggt ist
        if (Auth::check()) {
            // Benutzer-ID des eingeloggten Benutzers abrufen
            $userId = Auth::user()->id;

            // Favoritenvideos des Benutzers abfragen
            $favVideos = FavVideo::where('user_id', $userId)->get();

            // Response mit den gefundenen Favoritenvideos zurückgeben
            return response()->json($favVideos);
        }

        // Wenn der Benutzer nicht eingeloggt ist, eine entsprechende Fehlermeldung zurückgeben
        return response()->json(['error' => 'Benutzer ist nicht eingeloggt'], 401);
        
    }

    public function delFavVideo(Request $request)
    {
        $validatedData = $request->validate([
        'favVideoId' => ['required'],
    ]);

    if (Auth::check()) {
        // Benutzer-ID des eingeloggten Benutzers abrufen
        $userId = Auth::user()->id;

        // Favoritenvideo des Benutzers anhand der ID abrufen und löschen
        $favVideo = FavVideo::where('user_id', $userId)->where('video_id', $validatedData['favVideoId'])->first();

        // Überprüfen, ob das Favoritenvideo existiert und dem Benutzer gehört
        if ($favVideo) {
            // Das Favoritenvideo löschen
            $favVideo->delete();

            // Erfolgsantwort zurückgeben
            return response()->json(['message' => 'Favoritenvideo erfolgreich gelöscht']);
        } else {
            // Fehlerantwort zurückgeben, wenn das Favoritenvideo nicht gefunden wurde oder nicht dem Benutzer gehört
            return response()->json(['error' => 'Favoritenvideo nicht gefunden oder gehört nicht dem Benutzer'], 404);
        }
    }

    // Fehlerantwort zurückgeben, wenn der Benutzer nicht eingeloggt ist
    return response()->json(['error' => 'Benutzer ist nicht eingeloggt'], 401);
            
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
