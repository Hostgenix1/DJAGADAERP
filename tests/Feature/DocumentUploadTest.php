<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class DocumentUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_document_can_be_uploaded_to_an_employee(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        Permission::create(['name' => 'create-documents']);
        $user->givePermissionTo('create-documents');

        $employee = Employee::create(['name' => 'Test Employee']);

        $response = $this->actingAs($user)->post(route('documents.upload'), [
            'documentable_type' => Employee::class,
            'documentable_id' => $employee->id,
            'category' => 'contract',
            'title' => 'Employment contract',
            'file' => UploadedFile::fake()->create('contract.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasNoErrors();

        $document = Document::where('documentable_type', Employee::class)
            ->where('documentable_id', $employee->id)
            ->firstOrFail();

        $this->assertSame('Employment contract', $document->title);
        $this->assertSame('contract', $document->category);
        Storage::disk('local')->assertExists($document->file_path);
    }

    public function test_documents_cannot_be_attached_to_an_unsupported_model(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        Permission::create(['name' => 'create-documents']);
        $user->givePermissionTo('create-documents');

        $response = $this->actingAs($user)->post(route('documents.upload'), [
            'documentable_type' => User::class,
            'documentable_id' => $user->id,
            'category' => 'contract',
            'title' => 'Should be rejected',
            'file' => UploadedFile::fake()->create('contract.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('documentable_type');
        $this->assertDatabaseCount('documents', 0);
    }
}
