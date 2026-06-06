<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Chat\SendMessageRequest;
use App\Actions\Chat\GenerateRagResponse;
use Illuminate\Http\JsonResponse;
use Throwable;

class ChatController extends Controller
{
    public function __construct(
        private readonly GenerateRagResponse $ragAction
    ) {}

    public function __invoke(SendMessageRequest $request): JsonResponse
    {
        try {
            $reply = $this->ragAction->execute($request->validated('message'));

            return response()->json([
                'status' => 'success',
                'data' => [
                    'reply' => $reply
                ]
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('api.chat.process_error'),
                'debug' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}