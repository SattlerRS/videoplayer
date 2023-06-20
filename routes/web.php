<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Usercontroller;
use App\Http\Controllers\ApiController;

// Route für zurück zur Startseite, 
// überprüft ob der User eingeloggt ist und leitet auf die entsprechende Seite weiter
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    } else {
        return view('welcome');
    }
});

// Router für die Profilansicht
Route::get('/profile', function () {
    return view('profile');
})->name('profile');

// Route für die Authentifizierung
Auth::routes();

// Route für Home
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route für die API Schnittstelle searchInApi
Route::post('/home', [ApiController::class, 'searchInApi'])->name('searchInApi');

// Route für die API Schnittstelle getRandomVideos
Route::get('/getRamdomVideos', [ApiController::class, 'getRandomVideos'])->name('getRandomVideos');

// Route für die API Schnittstelle index
Route::post('/api/index', [ApiController::class, 'index'])->name('api.index');

// Route für die API Schnittstelle getFavVideos
Route::get('/api/getFavVideos', [ApiController::class, 'getFavVideos'])->name('api.getFavVideos');

// Route für die API Schnittstelle delFavVideo
Route::post('/api/delFavVideo', [ApiController::class, 'delFavVideo'])->name('api.delFavVideo');

// Route für das Updaten der Userdaten
Route::post('/user/update', [UserController::class, 'update'])->name('user.update');


