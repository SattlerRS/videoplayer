<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_YouTube;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\FavVideo;
use Datetime;
use DateInterval;

class apiController extends Controller
{
//     public function searchInApi(Request $request)
// {
    
//     $search = $request->validate([
//         'search' => ['required'],
//     ]);

//     // Hier kannst du die API-Codes tauschen
//     // $apiKey = 'AIzaSyC57jVf-kqK_LUtKPVIBn9ITX_fuTQtt14';
//     $apiKey = 'AIzaSyCoEWhLPxeFGbE-pSI3ve8TWW7g0EOwVDk';

//     //$url = 'https://www.googleapis.com/youtube/v3/search';
//     $url = 'https://www.googleapis.com/youtube/v3/search';
//     $params = [
//         'part' => 'snippet,id',
//         'q' => $search['search'],
//         'key' => $apiKey,
//         'maxResults' => 10,
//     ];

//     $response = file_get_contents($url . '?' . http_build_query($params));
//     $result = json_decode($response, true);

//     // Filtere die Videos nach gültiger ID
//     $filteredResults = [];
//     if (isset($result['items'])) {
//         foreach ($result['items'] as $item) {
//             if (isset($item['id']['videoId'])) {
//                 if (isset($videoResult['items']['contentDetails']['duration'])) { $item['duration'] = $videoResult['items']['contentDetails']['duration']; } else { $item['duration'] = 'Unknown'; }
//                 $filteredResults[] = $item;
//             }
//         }
//     }
    
//     // Ersetze das ursprüngliche Ergebnis mit den gefilterten Ergebnissen
    
//     $result['items'] = $filteredResults;

//     // Gib das Ergebnis als JSON zurück
//     return response()->json($result);
// }
public function searchInApi(Request $request)
{
    $search = $request->validate([
        'search' => ['required'],
    ]); 
    
    $apiKey = 'AIzaSyC57jVf-kqK_LUtKPVIBn9ITX_fuTQtt14';

    $searchUrl = 'https://www.googleapis.com/youtube/v3/search';
    $videoUrl = 'https://www.googleapis.com/youtube/v3/videos';

    $searchParams = [
        'part' => 'snippet',
        'q' => $search['search'],
        'key' => $apiKey,
        'maxResults' => 10,
    ];

    $searchResponse = file_get_contents($searchUrl . '?' . http_build_query($searchParams));
    $searchResult = json_decode($searchResponse, true);

    $filteredResults = [];

    if (isset($searchResult['items'])) {
        $videoIds = [];
        foreach ($searchResult['items'] as $item) {
            if (isset($item['id']['videoId'])) {
                $filteredResults[] = $item;
                $videoIds[] = $item['id']['videoId'];
            }
        }

        if (!empty($videoIds)) {
            $videoParams = [
                'part' => 'contentDetails',
                'id' => implode(',', $videoIds),
                'key' => $apiKey,
            ];

            $videoResponse = file_get_contents($videoUrl . '?' . http_build_query($videoParams));
            $videoResult = json_decode($videoResponse, true);

            if (isset($videoResult['items'])) {
                foreach ($filteredResults as &$item) {
                    foreach ($videoResult['items'] as $video) {
                        if ($item['id']['videoId'] === $video['id']) {
                            $duration = $video['contentDetails']['duration'];
                            $formattedDuration = $this->covtime($duration);
                            $item['duration'] = $formattedDuration;
                            break;
                        }
                    }
                }
            }
        }
    }

    $result['items'] = $filteredResults;

    return response()->json($result);
}
function covtime($youtube_time){
    $start = new DateTime('@0');
    $start->add(new DateInterval($youtube_time));

    if ($start->format('H') != 00) { // if hours exist, return entire duration
          if ($start->format('H')[0] == 0) { // if less than 10 hours, eliminate first zero
           $eliminate_zero = substr($start->format('H:i:s'),1);
          return $eliminate_zero;
          }
          else {
            return $start->format('H:i:s');
          }
    }
    elseif ($start->format('i')[0] == 0) {
            $eliminate_zero = substr($start->format('i:s'),1);
            return $eliminate_zero;
        }
    else {
    return $start->format('i:s');
    }
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
