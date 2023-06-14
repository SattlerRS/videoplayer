<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_YouTube;
class apiController extends Controller
{
    public function searchInApi(Request $request)
{
    $search = $request->validate([
        'search' => ['required'],
    ]);

    // Hier kannst du die API-Codes tauschen
    //$apiKey = 'AIzaSyC57jVf-kqK_LUtKPVIBn9ITX_fuTQtt14';
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
    
    public function index()
    {
        //
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
