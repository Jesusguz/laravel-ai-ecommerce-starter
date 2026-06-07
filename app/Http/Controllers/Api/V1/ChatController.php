<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Chat\SendMessageRequest;
use App\Actions\Chat\GenerateRagResponse;
use Illuminate\Http\JsonResponse;
use Throwable;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function stream(SendMessageRequest $request, GenerateRagResponse $action): StreamedResponse
    {
        return response()->stream(function () use ($request, $action) {
            $message = $request->validated('message');

            $context = $action->buildContext($message);

            $messages = [['role' => 'user', 'content' => $message]];
            $stream = $action->getAiEngine()->streamChat($messages, $context);

            foreach ($stream as $token) {
                echo "data: " . json_encode(['token' => $token]) . "\n\n";
                ob_flush();
                flush();
            }
            echo "data: [DONE]\n\n";
            ob_flush();
            flush();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}