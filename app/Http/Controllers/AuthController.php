<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle user login.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $request->authenticate();

            // Retrieve the authenticated user
            $user = User::where('email', $request->input('email'))->first();

            // Generate and retrieve a plain text access token for the user
            $authToken = $user->createToken('auth-token')->plainTextToken;

            // Return a JSON response with the access token
            return response()->json(['access_token' => $authToken]);
        } catch (ValidationException $e) {
          // Validation failed
          return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Other unexpected errors
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }
}
