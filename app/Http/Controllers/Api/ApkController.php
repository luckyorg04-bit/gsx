<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApkController extends Controller
{
    /**
     * Return the configured Parent Android app release metadata when present.
     */
    public function parentApp(): JsonResponse
    {
        return $this->releaseMetadata('parent_apk_detail.json', 'Parent');
    }

    /**
     * Return the configured Teacher Android app release metadata when present.
     */
    public function teacherApp(): JsonResponse
    {
        return $this->releaseMetadata('teacher_apk_detail.json', 'Teacher');
    }

    private function releaseMetadata(string $filename, string $audience): JsonResponse
    {
        $path = public_path('android-apk/' . $filename);

        if (! is_file($path) || ! is_readable($path)) {
            return response()->json([
                'status' => 'unavailable',
                'message' => $audience . ' app release metadata is not configured.',
            ], 404);
        }

        $metadata = json_decode((string) file_get_contents($path), true);

        if (! is_array($metadata)) {
            return response()->json([
                'status' => 'error',
                'message' => $audience . ' app release metadata is invalid.',
            ], 500);
        }

        return response()->json($metadata);
    }
}
