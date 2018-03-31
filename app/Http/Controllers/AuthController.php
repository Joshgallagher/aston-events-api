<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Resources\UserResource;
use App\Http\Requests\LoginUserRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class AuthController extends Controller
{
    /**
     * Issue a valid token to the requesting User.
     *
     * @param \App\Http\Requests\LoginUserRequest $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|\App\Http\Resources\UserResource
     */
    public function issueToken(LoginUserRequest $request)
    {
        $credentials = request(['email', 'password']);

        try {
            if (!$accessToken = auth()->attempt($credentials)) {
                return $this->errorResponse(
                    Response::HTTP_UNAUTHORIZED,
                    'Something was wrong with those details.'
                );
            }
        } catch (JWTException $e) {
            return $this->errorResponse(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Something went wrong.'
            );
        }

        $expiresIn = auth()->factory()->getTTL() * 60;

        return (new UserResource(auth()->user()))->additional([
            'meta' => [
                'access_token' => (string) $accessToken,
                'token_type' => (string) 'bearer',
                'expires_in' => (int) $expiresIn,
            ],
        ]);
    }

    /**
     * Revoke a Users token - invalidate the token.
     * A User must be authenticated to access this route - provide their bearer token.
     * A null, http no contant (204) response is returned on a successful invalidation.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function revokeToken(): SymfonyResponse
    {
        auth()->logout();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Formats a JSON error response.
     *
     * @param int    $responseCode
     * @param string $message
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function errorResponse(int $responseCode, string $message): SymfonyResponse
    {
        return response()->json([
            'errors' => [
                'code' => $responseCode,
                'message' => $message,
            ],
        ]);
    }
}
