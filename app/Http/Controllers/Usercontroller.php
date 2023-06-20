<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function update(Request $request)
    {
        // Daten validieren
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);

        // User abfragen und Userdaten ändern
        $user = Auth::user();
        $user->name = $request->input('name');
        $user->email = $request->input('email');


        // Wenn Password Sektion vorhanden wird diese Logik ausgeführt
        if ($request->has('passwordOrigin') && $request->has('password') && $request->has('passwordRepeat')) {
            $passwordOrigin = $request->input('passwordOrigin');
            $password = $request->input('password');
            $passwordRepeat = $request->input('passwordRepeat');

            // Prüft ob das eingegebene Passwort mit dem Userpasswort übereinstimmt
            if (Hash::check($passwordOrigin, $user->password)) {

                // Prüft ob die Passwörter nicht leer sind und min 8 Zeichen lang sind
                if ($password !== 'undefined' && strlen($password) >= 8 && $passwordRepeat !== 'undefined' && strlen($passwordRepeat) >= 8) {

                    // Prüft ob die beiden Passwörter übereinstimmen
                    if ($password === $passwordRepeat) {
                        // Hasht das neue Passwort
                        $user->password = Hash::make($password);
                    } else {
                        return response()->json(['message' => 'Die Passwörter stimmen nicht überein'], 422);
                    }
                } else {
                    return response()->json(['message' => 'Die Passwörter müssen min 8 Zeichen lang sein'], 422);
                }
            } else {
                return response()->json(['message' => 'Das aktuelle Passwort ist nicht korrekt'], 422);
            }
        }
        // Prüft ob ein Image dem Request beigefügt wurde und speichert es in den User
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('image'), $imageName);
            $user->image = $imageName;
        }

        // Überschreibt den User in der Datenbank
        $user->save();

        return response()->json(['message' => 'Benutzerdaten wurden erfolgreich aktualisiert']);
    }
}