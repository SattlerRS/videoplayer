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
public function searchInApi(Request $request)
{
    $search = $request->validate([
        'search' => ['required'],
    ]);
    //Hier sind Verschiedene Google-api-keys fals sie ausgegen

    // $apiKey = 'AIzaSyCoEWhLPxeFGbE-pSI3ve8TWW7g0EOwVDk';
    // $apiKey = 'AIzaSyC57jVf-kqK_LUtKPVIBn9ITX_fuTQtt14';
        $apiKey = 'AIzaSyC_th321p3wH1vHt_ZMfZdWF4bar0xgQnk';


    //Gibt die Länge des Videos zurück
    $searchUrl = 'https://www.googleapis.com/youtube/v3/search';
    //Gibt die Video-id, Tite und den link für das thumbnail zurück
    $videoUrl = 'https://www.googleapis.com/youtube/v3/videos';

    //Hier wird alles für die Anfrage mitgegeben (Welche Informationen, der Suchbegriff, der Api-Key und die anzahl der Resultate )
    $searchParams = [
        'part' => 'snippet',
        'q' => $search['search'],
        'key' => $apiKey,
        'maxResults' => 10,
    ];
    // Sende eine HTTP-Anfrage an eine bestimmte URL mit den angegebenen Parametern
    $searchResponse = file_get_contents($searchUrl . '?' . http_build_query($searchParams));
    // Dekodiere die JSON-Antwort in ein assoziatives Array
    $searchResult = json_decode($searchResponse, true);

    $filteredResults = [];

    // Hier wird überprüft ob das json object einen id enthält
    if (isset($searchResult['items'])) {
        $videoIds = [];
         // Iteriere über jedes Element im Array $searchResult['items']
        foreach ($searchResult['items'] as $item) {
            // Überprüfe, ob das Element den Schlüssel 'id' und innerhalb davon den Schlüssel 'videoId' enthält
            if (isset($item['id']['videoId'])) {
                // Füge das Element zum Array $filteredResults hinzu
                $filteredResults[] = $item;
                // Füge die Video-ID zum Array $videoIds hinzu
                $videoIds[] = $item['id']['videoId'];
            }
        }
            $videoParams = [
                'part' => 'contentDetails',// Die Teile der Videoinformationen, die abgerufen werden sollen
                'id' => implode(',', $videoIds),
                'key' => $apiKey,
            ];
            $videoResponse = file_get_contents($videoUrl . '?' . http_build_query($videoParams));
            $videoResult = json_decode($videoResponse, true);

            if (isset($videoResult['items'])) {
                // Iteriere über jedes Element in $filteredResults
                foreach ($filteredResults as &$item) {
                    // Iteriere über jedes Element in $videoResult['items']
                    foreach ($videoResult['items'] as $video) {
                        // Vergleiche die Video-ID des Elements in $filteredResults mit der Video-ID des Elements in $videoResult
                        if ($item['id']['videoId'] === $video['id']) {
                            // Extrahiere die Dauer des Videos aus $videoResult und formatiere sie
                            $duration = $video['contentDetails']['duration'];
                            $formattedDuration = $this->covtime($duration);
                             // Füge die formatierte Dauer dem Element in $filteredResults hinzu
                            $item['duration'] = $formattedDuration;
                            break;
                        }
                    }
                }
        }
    }

    $result['items'] = $filteredResults;
    // Das Array $filteredResults, das die gefilterten und modifizierten Ergebnisse enthält, wird dem Array $result unter dem Schlüssel 'items' zugewiesen.
    // Dadurch wird das Endergebnis strukturiert, indem die gefilterten Ergebnisse in einer passenden Form abgelegt werden.
    
    return response()->json($result);
    // Die Methode gibt eine JSON-Antwort zurück, die das Ergebnis enthält.
    // Die Funktion response() erstellt eine HTTP-Antwort, und die Methode json($result) konvertiert das Array $result in JSON-Format und gibt es als Teil der HTTP-Antwort zurück.
    // Dadurch wird sichergestellt, dass das Endergebnis der Methode als JSON-Objekt zurückgegeben wird, das die gefilterten Ergebnisse enthält.
}

public function getRandomVideos()
{
    // API-Schlüssel für YouTube
    $apiKey = 'AIzaSyC57jVf-kqK_LUtKPVIBn9ITX_fuTQtt14';

    // URL für die Suchanfrage an die YouTube API
    $searchUrl = 'https://www.googleapis.com/youtube/v3/search';
    // URL für die Videoinformationen von YouTube
    $videoUrl = 'https://www.googleapis.com/youtube/v3/videos';

    // Suchparameter für die YouTube API-Anfrage
    $searchParams = [
        'part' => 'snippet', // Nur den Snippet-Teil der Videoinformationen abrufen
        'order' => 'date', // Videos nach Datum sortieren
        'key' => $apiKey,
        'maxResults' => 50, // Große Anzahl von Videos abrufen
    ];

    // Suchanfrage an die YouTube API senden und die Antwort abrufen
    $searchResponse = file_get_contents($searchUrl . '?' . http_build_query($searchParams));
    // Die Antwort in ein assoziatives Array umwandeln
    $searchResult = json_decode($searchResponse, true);

    // Array für die gefilterten Ergebnisse vorbereiten
    $filteredResults = [];

    // Überprüfen, ob die Antwort 'items' enthält
    if (isset($searchResult['items'])) {
        shuffle($searchResult['items']); // Zufällige Reihenfolge der Videos

        // Die ersten 10 zufälligen Videos auswählen
        $randomVideos = array_slice($searchResult['items'], 0, 10);

        $videoIds = [];
        foreach ($randomVideos as $item) {
            // Überprüfen, ob das Video-Element den Schlüssel 'videoId' enthält
            if (isset($item['id']['videoId'])) {
                // Das Video zur Liste der gefilterten Ergebnisse hinzufügen
                $filteredResults[] = $item;
                // Die Video-ID zur Liste der Video-IDs hinzufügen
                $videoIds[] = $item['id']['videoId'];
            }
        }

        // Überprüfen, ob Video-IDs vorhanden sind
        if (!empty($videoIds)) {
            // Video-Parameter für die Videoinformationen erstellen
            $videoParams = [
                'part' => 'contentDetails', // Die Teile der Videoinformationen, die abgerufen werden sollen
                'id' => implode(',', $videoIds), // Video-IDs als kommaseparierter String übergeben
                'key' => $apiKey,
            ];

            // Videoanfrage an die YouTube API senden und die Antwort abrufen
            $videoResponse = file_get_contents($videoUrl . '?' . http_build_query($videoParams));
            // Die Antwort in ein assoziatives Array umwandeln
            $videoResult = json_decode($videoResponse, true);

            // Überprüfen, ob die Antwort 'items' enthält
            if (isset($videoResult['items'])) {
                foreach ($filteredResults as &$item) {
                    foreach ($videoResult['items'] as $video) {
                        // Überprüfen, ob die Video-ID des Elements mit der Video-ID des Videos übereinstimmt
                        if ($item['id']['videoId'] === $video['id']) {
                            $duration = $video['contentDetails']['duration'];
                            // Die Dauer des Videos formatieren
                            $formattedDuration = $this->covtime($duration);
                            // Die formatierte Dauer dem Element hinzufügen
                            $item['duration'] = $formattedDuration;
                            break;
                        }
                    }
                }
            }
        }
    }

    // Die gefilterten Ergebnisse dem Ergebnisarray zuweisen
    $result['items'] = $filteredResults;

    // Die Ergebnisse als JSON-Antwort zurückgeben
    return response()->json($result);
}


    function covtime($youtube_time){
        $start = new DateTime('@0');
        $start->add(new DateInterval($youtube_time));
    
        if ($start->format('H') != 00) {
            // Wenn Stunden vorhanden sind, wird die gesamte Dauer zurückgegeben
            if ($start->format('H')[0] == 0) {
                // Wenn die Stundenanzahl kleiner als 10 ist, wird die führende Null entfernt
                $eliminate_zero = substr($start->format('H:i:s'), 1);
                return $eliminate_zero;
            } else {
                return $start->format('H:i:s');
            }
        }
        elseif ($start->format('i')[0] == 0) {
            // Wenn keine Stunden vorhanden sind und die Minutenanzahl kleiner als 10 ist, wird die führende Null entfernt
            $eliminate_zero = substr($start->format('i:s'), 1);
            return $eliminate_zero;
        } else {
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
            $favVideo->duration = $request->input('Duration');
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
