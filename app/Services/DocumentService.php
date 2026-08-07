<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function store(Model $model, string $category, UploadedFile $file, string $title, ?string $notes = null): Document
    {
        $existing = Document::where('documentable_type', get_class($model))
            ->where('documentable_id', $model->id)
            ->where('category', $category)
            ->latest('version')
            ->first();

        $version = $existing ? $existing->version + 1 : 1;

        if ($existing) {
            $existing->update(['is_latest' => false]);
        }

        $path = $file->store('documents', 'local');

        return Document::create([
            'documentable_type' => get_class($model),
            'documentable_id' => $model->id,
            'category' => $category,
            'title' => $title,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
            'notes' => $notes,
            'version' => $version,
            'is_latest' => true,
            'created_by' => Auth::id(),
        ]);
    }

    public function delete(Document $document): bool
    {
        if (Storage::disk('local')->exists($document->file_path)) {
            Storage::disk('local')->delete($document->file_path);
        }

        return $document->delete();
    }

    public function query()
    {
        return Document::with(['documentable', 'creator'])
            ->latest('id');
    }

    public function forModel(Model $model)
    {
        return $this->query()
            ->where('documentable_type', get_class($model))
            ->where('documentable_id', $model->id)
            ->latest('version');
    }

    public function latestForModel(Model $model, ?string $category = null)
    {
        $query = $this->forModel($model)->where('is_latest', true);

        if ($category) {
            $query->where('category', $category);
        }

        return $query->get();
    }

    public function allVersions(Model $model, string $title)
    {
        return $this->forModel($model)
            ->where('title', $title)
            ->latest('version')
            ->get();
    }

    public function search(string $term, int $limit = 20): \Illuminate\Support\Collection
    {
        return Document::query()
            ->where('is_latest', true)
            ->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('original_name', 'like', "%{$term}%")
                    ->orWhere('notes', 'like', "%{$term}%");
            })
            ->with('documentable')
            ->limit($limit)
            ->get();
    }
}
