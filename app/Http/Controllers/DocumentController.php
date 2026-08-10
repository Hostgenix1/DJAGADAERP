<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function __construct(protected DocumentService $service)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('view-documents');

        $documents = $this->service->query()
            ->when($request->category, fn ($q) => $q->where('category', $request->category))
            ->paginate(25);

        $categories = ['contract', 'certificate', 'invoice', 'packing_list', 'shipping', 'import', 'export', 'other'];

        return view('documents.index', compact('documents', 'categories'));
    }

    public function search(Request $request)
    {
        $this->authorize('view-documents');

        $term = $request->input('q', '');

        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $results = $this->service->search($term)->map(fn (Document $d) => [
            'id' => $d->id,
            'title' => $d->title,
            'original_name' => $d->original_name,
            'category' => $d->category,
            'entity_type' => class_basename($d->documentable_type),
            'entity_id' => $d->documentable_id,
            'url' => route('documents.download', $d),
        ]);

        return response()->json($results);
    }

    public function upload(Request $request)
    {
        $this->authorize('create-documents');

        $request->validate([
            'documentable_type' => ['required', 'string'],
            'documentable_id' => ['required', 'integer'],
            'category' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'max:20480', 'extensions:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif,csv,txt'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $type = $request->documentable_type;

        $allowedTypes = [
            'App\\Models\\Customer',
            'App\\Models\\Invoice',
            'App\\Models\\Quote',
            'App\\Models\\Order',
            'App\\Models\\Product',
            'App\\Models\\Lead',
            'App\\Models\\Payment',
            'App\\Models\\Supplier',
            'App\\Models\\PurchaseOrder',
            'App\\Models\\SupplierBill',
        ];
        if (!in_array($type, $allowedTypes)) {
            return back()->withErrors(['documentable_type' => 'Invalid type.']);
        }

        $model = $type::find($request->documentable_id);

        if (! $model) {
            return back()->with('error', 'Record not found.');
        }

        $this->service->store($model, $request->category, $request->file('file'), $request->title, $request->notes);

        return back()->with('success', 'Document uploaded.');
    }

    public function download(Document $document): StreamedResponse
    {
        $this->authorize('view-documents');

        $path = $document->file_path;

        if (! Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return Storage::disk('local')->download($path, $document->original_name);
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete-documents');

        $this->service->delete($document);

        return back()->with('success', 'Document deleted.');
    }
}
