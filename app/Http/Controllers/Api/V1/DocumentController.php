<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Document\UploadDocumentRequest;
use App\Http\Resources\Api\V1\DocumentResource;
use App\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function upload(UploadDocumentRequest $request): JsonResponse
    {
        $file = $request->file('file');

        $uuid = Str::uuid();
        $extension = $file->getClientOriginalExtension();
        $filename = $uuid.'.'.$extension;

        $path = $file->storeAs('documents', $filename, 'local');

        if (! $path) {
            return response()->json([
                'meta' => [
                    'code' => 500,
                    'status' => 'error',
                    'message' => 'Failed to upload file',
                ],
            ], 500);
        }

        $metadata = [
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
            'size_kb' => round($file->getSize() / 1024, 2),
        ];

        $document = Document::create([
            'uuid' => $uuid,
            'title' => $request->validated('title') ?? $file->getClientOriginalName(),
            'description' => $request->validated('description'),
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'metadata' => $metadata,
        ]);

        return response()->json([
            'meta' => [
                'code' => 201,
                'status' => 'success',
                'message' => 'Document uploaded successfully',
            ],
            'data' => [
                'document' => DocumentResource::make($document->load('uploadedBy')),
            ],
        ], 201);
    }

    public function index(): JsonResponse
    {
        $documents = Document::with('uploadedBy')
            ->latest()
            ->paginate(15);

        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Documents retrieved successfully',
            ],
            'data' => [
                'documents' => DocumentResource::collection($documents),
                'pagination' => [
                    'total' => $documents->total(),
                    'per_page' => $documents->perPage(),
                    'current_page' => $documents->currentPage(),
                    'last_page' => $documents->lastPage(),
                ],
            ],
        ]);
    }

    public function show(string $uuid): JsonResponse
    {
        $document = Document::with('uploadedBy')
            ->where('uuid', $uuid)
            ->firstOrFail();

        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Document retrieved successfully',
            ],
            'data' => [
                'document' => DocumentResource::make($document),
            ],
        ]);
    }

    public function download(string $uuid): mixed
    {
        $document = Document::where('uuid', $uuid)->firstOrFail();

        if (! Storage::disk('local')->exists($document->path)) {
            return response()->json([
                'meta' => [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'File not found',
                ],
            ], 404);
        }

        $document->increment('download_count');
        $document->update(['last_downloaded_at' => now()]);

        return Storage::disk('local')->download($document->path, $document->original_name);
    }

    public function destroy(string $uuid): JsonResponse
    {
        $document = Document::where('uuid', $uuid)->firstOrFail();

        if (Storage::disk('local')->exists($document->path)) {
            Storage::disk('local')->delete($document->path);
        }

        $document->delete();

        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Document deleted successfully',
            ],
            'data' => [],
        ]);
    }
}
