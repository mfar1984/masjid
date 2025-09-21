<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\DocumentFolder;
use App\Models\DocumentShare;
use App\Models\Masjid;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DocumentSharingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $masjid1;
    protected $masjid2;
    protected $document;
    protected $folder;
    protected $role;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test masjids
        $this->masjid1 = Masjid::factory()->create(['kod_masjid' => 'MSJ001']);
        $this->masjid2 = Masjid::factory()->create(['kod_masjid' => 'MSJ002']);

        // Create test role
        $this->role = Role::factory()->create([
            'name' => 'Admin Masjid',
            'masjid_id' => $this->masjid1->id,
            'permissions' => json_encode(['documents' => ['create', 'read', 'update', 'delete', 'share']])
        ]);

        // Create test user
        $this->user = User::factory()->create([
            'masjid_id' => $this->masjid1->id,
            'role_id' => $this->role->id
        ]);

        // Create test document and folder
        $this->document = Document::factory()->create([
            'masjid_id' => $this->masjid1->id,
            'created_by' => $this->user->id
        ]);

        $this->folder = DocumentFolder::factory()->create([
            'masjid_id' => $this->masjid1->id,
            'created_by' => $this->user->id
        ]);
    }

    /** @test */
    public function can_get_sharing_data_for_document()
    {
        $this->actingAs($this->user);

        $response = $this->getJson("/api/documents/sharing/document/{$this->document->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'item',
                        'shared_masjids',
                        'access_level',
                        'share_link'
                    ]
                ]);
    }

    /** @test */
    public function can_share_document_with_masjid()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/api/documents/sharing/share', [
            'item_type' => 'document',
            'item_id' => $this->document->id,
            'kod_masjid' => 'MSJ002',
            'permission_level' => 'view'
        ]);

        $response->assertStatus(200)
                ->assertJson(['success' => true]);

        // Verify share was created
        $this->assertDatabaseHas('document_shares', [
            'shareable_type' => Document::class,
            'shareable_id' => $this->document->id,
            'shared_by_masjid_id' => $this->masjid1->id,
            'shared_with_masjid_id' => $this->masjid2->id,
            'permission_level' => 'view',
            'status' => 'active'
        ]);
    }

    /** @test */
    public function can_unshare_document_from_masjid()
    {
        $this->actingAs($this->user);

        // First create a share
        DocumentShare::create([
            'shareable_type' => Document::class,
            'shareable_id' => $this->document->id,
            'shared_by_masjid_id' => $this->masjid1->id,
            'shared_by_user_id' => $this->user->id,
            'shared_with_masjid_id' => $this->masjid2->id,
            'permission_level' => 'view',
            'status' => 'active'
        ]);

        $response = $this->postJson('/api/documents/sharing/unshare', [
            'item_type' => 'document',
            'item_id' => $this->document->id,
            'kod_masjid' => 'MSJ002'
        ]);

        $response->assertStatus(200)
                ->assertJson(['success' => true]);

        // Verify share was deactivated
        $this->assertDatabaseHas('document_shares', [
            'shareable_type' => Document::class,
            'shareable_id' => $this->document->id,
            'shared_by_masjid_id' => $this->masjid1->id,
            'shared_with_masjid_id' => $this->masjid2->id,
            'status' => 'inactive'
        ]);
    }

    /** @test */
    public function can_update_access_level()
    {
        $this->actingAs($this->user);

        // First create a share
        DocumentShare::create([
            'shareable_type' => Document::class,
            'shareable_id' => $this->document->id,
            'shared_by_masjid_id' => $this->masjid1->id,
            'shared_by_user_id' => $this->user->id,
            'shared_with_masjid_id' => $this->masjid2->id,
            'permission_level' => 'view',
            'status' => 'active'
        ]);

        $response = $this->postJson('/api/documents/sharing/access-level', [
            'item_type' => 'document',
            'item_id' => $this->document->id,
            'access_level' => 'anyone_with_link'
        ]);

        $response->assertStatus(200)
                ->assertJson(['success' => true]);

        // Verify public link was created
        $this->assertDatabaseHas('document_shares', [
            'shareable_type' => Document::class,
            'shareable_id' => $this->document->id,
            'shared_by_masjid_id' => $this->masjid1->id,
            'is_public_link' => true,
            'status' => 'active'
        ]);
    }

    /** @test */
    public function can_get_share_link()
    {
        $this->actingAs($this->user);

        $response = $this->getJson("/api/documents/sharing/link/document/{$this->document->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'share_link'
                    ]
                ]);

        // Verify public share was created
        $this->assertDatabaseHas('document_shares', [
            'shareable_type' => Document::class,
            'shareable_id' => $this->document->id,
            'shared_by_masjid_id' => $this->masjid1->id,
            'is_public_link' => true,
            'status' => 'active'
        ]);
    }

    /** @test */
    public function cannot_share_with_invalid_kod_masjid()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/api/documents/sharing/share', [
            'item_type' => 'document',
            'item_id' => $this->document->id,
            'kod_masjid' => 'MSJ999',
            'permission_level' => 'view'
        ]);

        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Kod Masjid tidak ditemui'
                ]);
    }

    /** @test */
    public function cannot_share_with_own_masjid()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/api/documents/sharing/share', [
            'item_type' => 'document',
            'item_id' => $this->document->id,
            'kod_masjid' => 'MSJ001', // Same as user's masjid
            'permission_level' => 'view'
        ]);

        $response->assertStatus(400)
                ->assertJson([
                    'success' => false,
                    'message' => 'Tidak boleh berkongsi dengan masjid sendiri'
                ]);
    }

    /** @test */
    public function shared_documents_appear_in_shared_view()
    {
        $this->actingAs($this->user);

        // Create a share from another masjid to this user's masjid
        DocumentShare::create([
            'shareable_type' => Document::class,
            'shareable_id' => $this->document->id,
            'shared_by_masjid_id' => $this->masjid2->id,
            'shared_by_user_id' => $this->user->id,
            'shared_with_masjid_id' => $this->masjid1->id,
            'permission_level' => 'view',
            'status' => 'active'
        ]);

        $response = $this->get('/documents?type=shared');

        $response->assertStatus(200);
        // Additional assertions can be added to check if shared documents appear
    }

    /** @test */
    public function can_access_public_shared_document()
    {
        // Create a public share (no shared_with_masjid_id for public links)
        $publicShare = DocumentShare::create([
            'shareable_type' => Document::class,
            'shareable_id' => $this->document->id,
            'shared_by_masjid_id' => $this->masjid1->id,
            'shared_by_user_id' => $this->user->id,
            'shared_with_masjid_id' => null, // Public links don't have target masjid
            'is_public_link' => true,
            'share_token' => 'public-share-token-123',
            'status' => 'active'
        ]);

        // Access public share without authentication
        $response = $this->get("/share/{$publicShare->share_token}");

        $response->assertStatus(200);
        // Additional assertions can be added to check document access
    }

    /** @test */
    public function sharing_modal_api_returns_real_user_data()
    {
        $this->actingAs($this->user);

        $response = $this->getJson("/api/documents/sharing/document/{$this->document->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'current_user' => [
                            'id' => $this->user->id,
                            'name' => $this->user->name,
                            'email' => $this->user->email,
                            'initials' => $this->user->initials,
                            'role' => $this->user->role->name,
                            'masjid' => [
                                'id' => $this->user->masjid->id,
                                'nama' => $this->user->masjid->nama,
                            ]
                        ],
                        'item' => [
                            'type' => 'document',
                            'id' => $this->document->id,
                            'name' => $this->document->name
                        ],
                        'shared_masjids' => [],
                        'access_level' => 'restricted',
                        'share_link' => null
                    ]
                ]);

        // Verify user initials are calculated correctly
        $this->assertEquals($this->user->initials, $response->json('data.current_user.initials'));

        // Verify role name is returned
        $this->assertEquals($this->user->role->name, $response->json('data.current_user.role'));

        // Verify masjid data is included
        $this->assertEquals($this->user->masjid->nama, $response->json('data.current_user.masjid.nama'));
    }

    /** @test */
    public function sharing_modal_api_handles_user_without_role_gracefully()
    {
        // Create user without role
        $userWithoutRole = User::factory()->create([
            'masjid_id' => $this->masjid1->id,
            'role_id' => null
        ]);

        $this->actingAs($userWithoutRole);

        $response = $this->getJson("/api/documents/sharing/document/{$this->document->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'current_user' => [
                            'id' => $userWithoutRole->id,
                            'name' => $userWithoutRole->name,
                            'email' => $userWithoutRole->email,
                            'role' => 'Owner' // Default fallback
                        ]
                    ]
                ]);
    }

    /** @test */
    public function sharing_modal_api_handles_user_without_masjid_gracefully()
    {
        // Create user without masjid
        $userWithoutMasjid = User::factory()->create([
            'masjid_id' => null,
            'role_id' => $this->role->id
        ]);

        $this->actingAs($userWithoutMasjid);

        $response = $this->getJson("/api/documents/sharing/document/{$this->document->id}");

        $response->assertStatus(404); // Should not be able to access document from different masjid
    }
}
