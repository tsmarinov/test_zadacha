<?php

namespace App\Http\Controllers\Rest\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Generate a consistent JSON response.
     *
     * @param int $code
     * @param mixed|null $data
     * @param array $validationErrors
     * @param string|null $error
     *
     * @return JsonResponse
     */
    protected function jsonResponse(
        int    $code,
        mixed  $data = null,
        array  $validationErrors = [],
        string $error = null
    ): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'data' => $data,
            'validation_errors' => $validationErrors,
            'error' => $error
        ], 200);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return $this->jsonResponse(-1, null, [], 'Login information is invalid.');
        }

        $user = User::where('email', $request['email'])
            ->firstOrFail();
        $expirationDurationInMinutes = config('sanctum.expiration', 60);
        $expirationDuration = \DateInterval::createFromDateString("$expirationDurationInMinutes minutes");

        // Clear previous expired tokens
        $user->tokens()->where('created_at', '<', now()->sub($expirationDuration))->delete();

        $token = $user->createToken('authToken')->plainTextToken;

        return $this->jsonResponse(0, [
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
