<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\DocumentFolder;
use App\Models\Masjid;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DocumentBasicTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function document_model_can_be_created()
    {
        $masjid = Masjid::create([
            'name' => 'Test Masjid',
            'email' => 'test@masjid.com',
            'phone' => '0123456789',
            'address' => 'Test Address',
            'status' => 'Aktif'
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@user.com',
            'password' => bcrypt('password'),
            'masjid_id' => $masjid->id,
            'role' => 'Admin Masjid',
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

        $document = Document::create([
            'name' => 'Test Document',
            'description' => 'This is a test document',
            'original_filename' => 'test.pdf',
            'file_path' => 'documents/test.pdf',
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 1024,
            'file_hash' => 'test-hash',
            'masjid_id' => $masjid->id,
            'created_by' => $user->id,
        ]);

        $this->assertDatabaseHas('documents', [
            'name' => 'Test Document',
            'masjid_id' => $masjid->id,
        ]);

        $this->assertEquals('Test Document', $document->name);
        $this->assertEquals($masjid->id, $document->masjid_id);
    }

    /** @test */
    public function document_folder_model_can_be_created()
    {
        $masjid = Masjid::create([
            'name' => 'Test Masjid',
            'email' => 'test@masjid.com',
            'phone' => '0123456789',
            'address' => 'Test Address',
            'status' => 'Aktif'
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@user.com',
            'password' => bcrypt('password'),
            'masjid_id' => $masjid->id,
            'role' => 'Admin Masjid',
            'permissions' => []
        ]);

        $folder = DocumentFolder::create([
            'name' => 'Test Folder',
            'description' => 'This is a test folder',
            'color' => '#3B82F6',
            'path' => 'Test Folder',
            'masjid_id' => $masjid->id,
            'created_by' => $user->id,
        ]);

        $this->assertDatabaseHas('document_folders', [
            'name' => 'Test Folder',
            'masjid_id' => $masjid->id,
        ]);

        $this->assertEquals('Test Folder', $folder->name);
        $this->assertEquals($masjid->id, $folder->masjid_id);
    }

    /** @test */
    public function document_belongs_to_folder()
    {
        $masjid = Masjid::create([
            'name' => 'Test Masjid',
            'email' => 'test@masjid.com',
            'phone' => '0123456789',
            'address' => 'Test Address',
            'status' => 'Aktif'
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@user.com',
            'password' => bcrypt('password'),
            'masjid_id' => $masjid->id,
            'role' => 'Admin Masjid',
            'permissions' => []
        ]);

        $folder = DocumentFolder::create([
            'name' => 'Test Folder',
            'color' => '#3B82F6',
            'path' => 'Test Folder',
            'masjid_id' => $masjid->id,
            'created_by' => $user->id,
        ]);

        $document = Document::create([
            'name' => 'Test Document',
            'original_filename' => 'test.pdf',
            'file_path' => 'documents/test.pdf',
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 1024,
            'file_hash' => 'test-hash',
            'folder_id' => $folder->id,
            'masjid_id' => $masjid->id,
            'created_by' => $user->id,
        ]);

        $this->assertEquals($folder->id, $document->folder_id);
        $this->assertEquals('Test Folder', $document->folder->name);
    }

    /** @test */
    public function folder_has_many_documents()
    {
        $masjid = Masjid::create([
            'name' => 'Test Masjid',
            'email' => 'test@masjid.com',
            'phone' => '0123456789',
            'address' => 'Test Address',
            'status' => 'Aktif'
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@user.com',
            'password' => bcrypt('password'),
            'masjid_id' => $masjid->id,
            'role' => 'Admin Masjid',
            'permissions' => []
        ]);

        $folder = DocumentFolder::create([
            'name' => 'Test Folder',
            'color' => '#3B82F6',
            'path' => 'Test Folder',
            'masjid_id' => $masjid->id,
            'created_by' => $user->id,
        ]);

        Document::create([
            'name' => 'Document 1',
            'original_filename' => 'doc1.pdf',
            'file_path' => 'documents/doc1.pdf',
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 1024,
            'file_hash' => 'hash1',
            'folder_id' => $folder->id,
            'masjid_id' => $masjid->id,
            'created_by' => $user->id,
        ]);

        Document::create([
            'name' => 'Document 2',
            'original_filename' => 'doc2.pdf',
            'file_path' => 'documents/doc2.pdf',
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 2048,
            'file_hash' => 'hash2',
            'folder_id' => $folder->id,
            'masjid_id' => $masjid->id,
            'created_by' => $user->id,
        ]);

        $this->assertEquals(2, $folder->documents()->count());
        $this->assertEquals('Document 1', $folder->documents()->first()->name);
    }

    /** @test */
    public function document_has_masjid_scope()
    {
        $masjid1 = Masjid::create([
            'name' => 'Masjid 1',
            'email' => 'masjid1@test.com',
            'phone' => '0123456789',
            'address' => 'Address 1',
            'status' => 'Aktif'
        ]);

        $masjid2 = Masjid::create([
            'name' => 'Masjid 2',
            'email' => 'masjid2@test.com',
            'phone' => '0123456789',
            'address' => 'Address 2',
            'status' => 'Aktif'
        ]);

        $user1 = User::create([
            'name' => 'User 1',
            'email' => 'user1@test.com',
            'password' => bcrypt('password'),
            'masjid_id' => $masjid1->id,
            'role' => 'Admin Masjid',
            'permissions' => []
        ]);

        $user2 = User::create([
            'name' => 'User 2',
            'email' => 'user2@test.com',
            'password' => bcrypt('password'),
            'masjid_id' => $masjid2->id,
            'role' => 'Admin Masjid',
            'permissions' => []
        ]);

        // Create documents for different masjids
        Document::create([
            'name' => 'Masjid 1 Document',
            'original_filename' => 'doc1.pdf',
            'file_path' => 'documents/doc1.pdf',
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 1024,
            'file_hash' => 'hash1',
            'masjid_id' => $masjid1->id,
            'created_by' => $user1->id,
        ]);

        Document::create([
            'name' => 'Masjid 2 Document',
            'original_filename' => 'doc2.pdf',
            'file_path' => 'documents/doc2.pdf',
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 2048,
            'file_hash' => 'hash2',
            'masjid_id' => $masjid2->id,
            'created_by' => $user2->id,
        ]);

        // Test that documents are isolated by masjid
        $this->assertEquals(2, Document::count()); // Total documents
        $this->assertEquals(1, Document::where('masjid_id', $masjid1->id)->count());
        $this->assertEquals(1, Document::where('masjid_id', $masjid2->id)->count());
    }
}
