<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Models\DocumentFolder;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateHashTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:generate-hash-tokens {--force : Force regenerate existing tokens}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate hash tokens for existing documents and folders for Google Drive style URLs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating hash tokens for documents and folders...');

        $force = $this->option('force');

        // Generate tokens for folders
        $this->info('Processing folders...');
        $foldersQuery = DocumentFolder::query();

        if (!$force) {
            $foldersQuery->whereNull('hash_token');
        }

        $folders = $foldersQuery->get();
        $folderCount = 0;

        foreach ($folders as $folder) {
            if ($force || !$folder->hash_token) {
                $folder->generateHashToken();
                $folderCount++;
            }
        }

        $this->info("Generated hash tokens for {$folderCount} folders.");

        // Generate tokens for documents
        $this->info('Processing documents...');
        $documentsQuery = Document::query();

        if (!$force) {
            $documentsQuery->whereNull('hash_token');
        }

        $documents = $documentsQuery->get();
        $documentCount = 0;

        foreach ($documents as $document) {
            if ($force || !$document->hash_token) {
                $document->generateHashToken();
                $documentCount++;
            }
        }

        $this->info("Generated hash tokens for {$documentCount} documents.");
        $this->info('Hash token generation completed!');

        return Command::SUCCESS;
    }
}
