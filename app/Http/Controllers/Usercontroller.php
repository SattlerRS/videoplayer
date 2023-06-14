<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function update(Request $request)
    {
        // Validation rules
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $user->name = $request->input('name');
        $user->email = $request->input('email');


        if ($request->has('passwordOrigin') && $request->has('password') && $request->has('passwordRepeat')) {
            $passwordOrigin = $request->input('passwordOrigin');
            $password = $request->input('password');
            $passwordRepeat = $request->input('passwordRepeat');

            if (Hash::check($passwordOrigin, $user->password)) {
                
                if($password !== null && strlen($password) >=8 && $passwordRepeat !== null && strlen($passwordRepeat) >= 8 ){

                    if ($password === $passwordRepeat) {
                        $user->password = Hash::make($password);
                    } else {
                        return response()->json(['message' => 'Die Passwörter stimmen nicht überein'], 422);
                    }
                }
                else{
                        return response()->json(['message' => 'Die Passwörter müssen min 8 Zeichen lang sein'], 422);
                }
            } else {
                return response()->json(['message' => 'Das aktuelle Passwort ist nicht korrekt'], 422);
            }
        } 

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('image'), $imageName);
            $user->image = $imageName;
        }

        $user->save();

        return response()->json(['message' => 'Benutzerdaten wurden erfolgreich aktualisiert']);
    }
}