<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmailConfirmationController extends Controller
{
    /**
     * Validate the confirmation token and respond appropriately.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!$user = User::where('confirmation_token', request('token'))->first()) {
            return response()->json([
                'errors' => [
                    'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'message' => 'The confirmation token is invalid.',
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->confirm();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
