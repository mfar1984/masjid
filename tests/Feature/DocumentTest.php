<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\DocumentFolder;
use App\Models\DocumentShare;
use App\Models\Masjid;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $masjid1;
    protected $masjid2;
    protected $user1;
    protected $user2;
    protected $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test masjids
        $this->masjid1 = Masjid::factory()->create(['name' => 'Masjid Test 1']);
        $this->masjid2 = Masjid::factory()->create(['name' => 'Masjid Test 2']);

        // Create test users
        $this->user1 = User::factory()->create([
            'masjid_id' => $this->masjid1->id,
            'permissions' => [
                'documents' => [
                    'create' => '1',
                    'read' => '1',
                    'update' => '1',
                    'delete' => '1',
                    'share' => '1'
                ]
            ]
        ]);

        $this->user2 = User::factory()->create([
            'masjid_id' => $this->masjid2->id,
            'permissions' => [
                'documents' => [
                    'create' => '1',
                    'read' => '1',
                    'update' => '1',
                    'delete' => '1',
                    'share' => '1'
                ]
            ]
        ]);

        $this->superAdmin = User::factory()->create([
            'role' => 'Super Admin',
            'masjid_id' => null,
            'permissions' => [
                'documents' => [
                    'create' => '1',
                    'read' => '1',
                    'update' => '1',
                    'delete' => '1',
                    'share' => '1'
                ]
            ]
        ]);

        // Setup fake storage
        Storage::fake('public');
    }

    /** @test */
    public function user_can_access_documents_index()
    {
        $response = $this->actingAs($this->user1)
            ->get(route('documents.index'));

        $response->assertStatus(200);
        $response->assertViewIs('documents.index');
    }

    /** @test */
    public function user_without_permission_cannot_access_documents()
    {
        $userWithoutPermission = User::factory()->create([
            'masjid_id' => $this->masjid1->id,
            'permissions' => []
        ]);

        $response = $this->actingAs($userWithoutPermission)
            ->get(route('documents.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_create_document()
    {
        $file = UploadedFile::fake()->create('test-document.pdf', 1024);

        $response = $this->actingAs($this->user1)
            ->post(route('documents.store'), [
                'name' => 'Test Document',
                'description' => 'This is a test document',
                'file' => $file,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('documents', [
            'name' => 'Test Document',
            'description' => 'This is a test document',
            'masjid_id' => $this->masjid1->id,
            'created_by' => $this->user1->id,
        ]);

        // Check file was stored
        $document = Document::where('name', 'Test Document')->first();
        Storage::disk('public')->assertExists($document->file_path);
    }

    /** @test */
    public function documents_are_isolated_by_masjid()
    {
        // Create documents for different masjids
        $document1 = Document::factory()->create([
            'masjid_id' => $this->masjid1->id,
            'name' => 'Masjid 1 Document'
        ]);

        $document2 = Document::factory()->create([
            'masjid_id' => $this->masjid2->id,
            'name' => 'Masjid 2 Document'
        ]);

        // User 1 should only see their masjid's documents
        $response = $this->actingAs($this->user1)
            ->get(route('documents.index'));

        $response->assertStatus(200);
        $response->assertSee('Masjid 1 Document');
        $response->assertDontSee('Masjid 2 Document');

        // User 2 should only see their masjid's documents
        $response = $this->actingAs($this->user2)
            ->get(route('documents.index'));

        $response->assertStatus(200);
        $response->assertSee('Masjid 2 Document');
        $response->assertDontSee('Masjid 1 Document');
    }

    /** @test */
    public function super_admin_can_see_all_documents()
    {
        // Create documents for different masjids
        $document1 = Document::factory()->create([
            'masjid_id' => $this->masjid1->id,
            'name' => 'Masjid 1 Document'
        ]);

        $document2 = Document::factory()->create([
            'masjid_id' => $this->masjid2->id,
            'name' => 'Masjid 2 Document'
        ]);

        // Super Admin should see all documents
        $response = $this->actingAs($this->superAdmin)
            ->get(route('documents.index'));

        $response->assertStatus(200);
        $response->assertSee('Masjid 1 Document');
        $response->assertSee('Masjid 2 Document');
    }

    /** @test */
    public function user_cannot_access_other_masjid_document()
    {
        $document = Document::factory()->create([
            'masjid_id' => $this->masjid2->id,
            'name' => 'Other Masjid Document'
        ]);

        $response = $this->actingAs($this->user1)
            ->get(route('documents.show', $document));

        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_share_document_with_another_masjid()
    {
        $document = Document::factory()->create([
            'masjid_id' => $this->masjid1->id,
            'created_by' => $this->user1->id
        ]);

        $response = $this->actingAs($this->user1)
            ->post(route('documents.share', $document), [
                'shared_with_masjid_id' => $this->masjid2->id,
                'permission_level' => 'view',
                'can_download' => true,
                'can_share_further' => false,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('document_shares', [
            'shareable_type' => Document::class,
            'shareable_id' => $document->id,
            'shared_by_masjid_id' => $this->masjid1->id,
            'shared_with_masjid_id' => $this->masjid2->id,
            'permission_level' => 'view',
        ]);

        // Document should be marked as shared
        $this->assertTrue($document->fresh()->is_shared);
    }

    /** @test */
    public function user_can_access_shared_document()
    {
        $document = Document::factory()->create([
            'masjid_id' => $this->masjid1->id,
            'created_by' => $this->user1->id
        ]);

        // Share document with masjid 2
        DocumentShare::create([
            'shareable_type' => Document::class,
            'shareable_id' => $document->id,
            'shared_by_masjid_id' => $this->masjid1->id,
            'shared_by_user_id' => $this->user1->id,
            'shared_with_masjid_id' => $this->masjid2->id,
            'permission_level' => 'view',
            'status' => 'active',
        ]);

        // User 2 should now be able to access the shared document
        $response = $this->actingAs($this->user2)
            ->get(route('documents.show', $document));

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_create_folder()
    {
        $response = $this->actingAs($this->user1)
            ->post(route('document-folders.store'), [
                'name' => 'Test Folder',
                'description' => 'This is a test folder',
                'color' => '#3B82F6',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('document_folders', [
            'name' => 'Test Folder',
            'description' => 'This is a test folder',
            'masjid_id' => $this->masjid1->id,
            'created_by' => $this->user1->id,
        ]);
    }

    /** @test */
    public function folders_are_isolated_by_masjid()
    {
        // Create folders for different masjids
        $folder1 = DocumentFolder::factory()->create([
            'masjid_id' => $this->masjid1->id,
            'name' => 'Masjid 1 Folder'
        ]);

        $folder2 = DocumentFolder::factory()->create([
            'masjid_id' => $this->masjid2->id,
            'name' => 'Masjid 2 Folder'
        ]);

        // User 1 should only see their masjid's folders
        $response = $this->actingAs($this->user1)
            ->get(route('documents.index'));

        $response->assertStatus(200);
        $response->assertSee('Masjid 1 Folder');
        $response->assertDontSee('Masjid 2 Folder');
    }

    /** @test */
    public function user_can_download_document()
    {
        $document = Document::factory()->create([
            'masjid_id' => $this->masjid1->id,
            'file_path' => 'documents/test.pdf',
            'original_filename' => 'test.pdf'
        ]);

        // Create fake file
        Storage::disk('public')->put($document->file_path, 'fake file content');

        $response = $this->actingAs($this->user1)
            ->get(route('documents.download', $document));

        $response->assertStatus(200);
        $response->assertHeader('content-disposition', 'attachment; filename=test.pdf');

        // Check download count was incremented
        $this->assertEquals(1, $document->fresh()->download_count);
    }

    /** @test */
    public function user_can_toggle_document_star()
    {
        $document = Document::factory()->create([
            'masjid_id' => $this->masjid1->id,
            'is_starred' => false
        ]);

        $response = $this->actingAs($this->user1)
            ->post(route('documents.toggle-star', $document));

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'is_starred' => true]);

        $this->assertTrue($document->fresh()->is_starred);
    }

    /** @test */
    public function user_can_delete_document()
    {
        $document = Document::factory()->create([
            'masjid_id' => $this->masjid1->id,
            'file_path' => 'documents/test.pdf'
        ]);

        // Create fake file
        Storage::disk('public')->put($document->file_path, 'fake file content');

        $response = $this->actingAs($this->user1)
            ->delete(route('documents.destroy', $document));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('documents', ['id' => $document->id]);
        Storage::disk('public')->assertMissing($document->file_path);
    }

    /** @test */
    public function user_cannot_delete_other_masjid_document()
    {
        $document = Document::factory()->create([
            'masjid_id' => $this->masjid2->id
        ]);

        $response = $this->actingAs($this->user1)
            ->delete(route('documents.destroy', $document));

        $response->assertStatus(403);
        $this->assertDatabaseHas('documents', ['id' => $document->id]);
    }

    /** @test */
    public function user_can_update_document()
    {
        $document = Document::factory()->create([
            'masjid_id' => $this->masjid1->id,
            'name' => 'Original Name',
            'description' => 'Original Description'
        ]);

        $response = $this->actingAs($this->user1)
            ->put(route('documents.update', $document), [
                'name' => 'Updated Name',
                'description' => 'Updated Description',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('documents', [
            'id' => $document->id,
            'name' => 'Updated Name',
            'description' => 'Updated Description',
        ]);
    }

    /** @test */
    public function user_can_search_documents()
    {
        Document::factory()->create([
            'masjid_id' => $this->masjid1->id,
            'name' => 'Important Report'
        ]);

        Document::factory()->create([
            'masjid_id' => $this->masjid1->id,
            'name' => 'Meeting Minutes'
        ]);

        $response = $this->actingAs($this->user1)
            ->get(route('documents.index', ['search' => 'Report']));

        $response->assertStatus(200);
        $response->assertSee('Important Report');
        $response->assertDontSee('Meeting Minutes');
    }

    /** @test */
    public function expired_shares_cannot_be_accessed()
    {
        $document = Document::factory()->create([
            'masjid_id' => $this->masjid1->id
        ]);

        // Create expired share
        DocumentShare::create([
            'shareable_type' => Document::class,
            'shareable_id' => $document->id,
            'shared_by_masjid_id' => $this->masjid1->id,
            'shared_by_user_id' => $this->user1->id,
            'shared_with_masjid_id' => $this->masjid2->id,
            'permission_level' => 'view',
            'status' => 'active',
            'expires_at' => now()->subDay(), // Expired yesterday
        ]);

        // User 2 should not be able to access expired shared document
        $response = $this->actingAs($this->user2)
            ->get(route('documents.show', $document));

        $response->assertStatus(403);
    }

    /** @test */
    public function folder_document_count_respects_masjid_isolation()
    {
        // Create a folder belonging to masjid1
        $folder = DocumentFolder::factory()->create([
            'masjid_id' => $this->masjid1->id,
            'name' => 'Test Folder'
        ]);

        // Create documents from different sources
        $masjidDocument = Document::factory()->create([
            'folder_id' => $folder->id,
            'masjid_id' => $this->masjid1->id,
            'name' => 'Masjid 1 Document'
        ]);

        $superAdminDocument = Document::factory()->create([
            'folder_id' => $folder->id,
            'masjid_id' => null, // Super admin document
            'name' => 'Super Admin Document'
        ]);

        // Test direct method calls for data isolation
        $this->assertEquals(2, $folder->getTotalDocuments(null)); // Super admin sees all
        $this->assertEquals(1, $folder->getTotalDocuments($this->masjid1->id)); // Masjid 1 sees only theirs
        $this->assertEquals(0, $folder->getTotalDocuments($this->masjid2->id)); // Masjid 2 sees none
        $this->assertEquals(0, $folder->getTotalDocuments(999)); // Non-existent masjid sees none

        // Test size calculation isolation
        $this->assertGreaterThan(0, $folder->getTotalSize(null)); // Super admin sees all sizes
        $this->assertGreaterThan(0, $folder->getTotalSize($this->masjid1->id)); // Masjid 1 sees their size
        $this->assertEquals(0, $folder->getTotalSize($this->masjid2->id)); // Masjid 2 sees no size
    }

    /** @test */
    public function folder_count_in_controller_respects_user_context()
    {
        // Create a folder belonging to masjid1
        $folder = DocumentFolder::factory()->create([
            'masjid_id' => $this->masjid1->id,
            'name' => 'Mixed Content Folder'
        ]);

        // Create documents from different sources
        Document::factory()->create([
            'folder_id' => $folder->id,
            'masjid_id' => $this->masjid1->id,
            'name' => 'Masjid Document'
        ]);

        Document::factory()->create([
            'folder_id' => $folder->id,
            'masjid_id' => null, // Super admin document
            'name' => 'Super Admin Document'
        ]);

        // Test Super Admin view - should see count of 2
        $response = $this->actingAs($this->superAdmin)
            ->get(route('documents.index'));

        $response->assertStatus(200);
        $folders = $response->viewData('folders');
        $testFolder = $folders->where('name', 'Mixed Content Folder')->first();
        $this->assertEquals(2, $testFolder->total_documents);

        // Test Masjid Admin view - should see count of 1
        $response = $this->actingAs($this->user1)
            ->get(route('documents.index'));

        $response->assertStatus(200);
        $folders = $response->viewData('folders');
        $testFolder = $folders->where('name', 'Mixed Content Folder')->first();
        $this->assertEquals(1, $testFolder->total_documents);

        // Test other masjid admin - should not see the folder at all
        $response = $this->actingAs($this->user2)
            ->get(route('documents.index'));

        $response->assertStatus(200);
        $folders = $response->viewData('folders');
        $this->assertNull($folders->where('name', 'Mixed Content Folder')->first());
    }
}
