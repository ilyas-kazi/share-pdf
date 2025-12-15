<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'description' => $this->description,
            'original_name' => $this->original_name,
            'preview_image' => $this->preview_image,
            'metadata' => $this->metadata,
            'download_count' => $this->download_count,
            'last_downloaded_at' => $this->last_downloaded_at?->toISOString(),
            'uploaded_at' => $this->created_at?->toISOString(),
            'uploaded_by' => UserResource::make($this->whenLoaded('uploadedBy')),
            'last_modified_at' => $this->updated_at?->toISOString(),
            'last_modified_by' => UserResource::make($this->whenLoaded('updatedBy')),
        ];
    }
}
