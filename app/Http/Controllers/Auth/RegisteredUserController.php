<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse; // Import JsonResponse
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Database\QueryException; // Import QueryException

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "lowercase",
                "email",
                "max:255",
                "unique:users",
            ],
            "password" => ["required", "confirmed", Rules\Password::defaults()],
        ]);

        try {
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "role" => "user", // Default role
            ]);

            event(new Registered($user));

            Auth::login($user);

            return response()->json(
                [
                    "message" => "User registered successfully",
                    "user" => $user,
                ],
                201
            );
        } catch (QueryException $e) {
            if ($e->getCode() === "23000") {
                return response()->json(
                    [
                        "message" => "The email address is already in use.",
                    ],
                    409
                );
            }

            return response()->json(
                [
                    "message" =>
                        "An error occurred while registering the user.",
                ],
                500
            );
        }
    }
}
