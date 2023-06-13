<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function update(Request $request)
    {
        // Validation rules
        $request->validate([
            'name' => 'required|min:4|string|max:255',
            'email' => 'required|email|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $user->name = $request->input('name');
        $user->email = $request->input('email');

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