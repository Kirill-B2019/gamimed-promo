<?php

namespace Tests\Feature;

use App\Support\PublicDownload;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PublicDownloadTest extends TestCase
{
    public function test_local_download_is_hidden_when_file_is_missing(): void
    {
        $this->assertFalse(PublicDownload::isAvailable('/downloads/missing-whitepaper.pdf'));
        $this->assertFalse(PublicDownload::isAvailable(null));
        $this->assertFalse(PublicDownload::isAvailable(''));
        $this->assertFalse(PublicDownload::isAvailable('/images/pattern-geometry.svg'));
    }

    public function test_local_download_is_available_when_pdf_exists(): void
    {
        $path = public_path('downloads/gamimed-whitepaper-ar.pdf');
        File::ensureDirectoryExists(dirname($path));
        File::put($path, "%PDF-1.4\n%test\n");
        clearstatcache(true, $path);

        try {
            $this->assertTrue(PublicDownload::isAvailable('/downloads/gamimed-whitepaper-ar.pdf'));
            $this->assertTrue(PublicDownload::isAvailable('/downloads/gamimed-whitepaper-ar.pdf?v=1'));
        } finally {
            File::delete($path);
            clearstatcache(true, $path);
        }
    }

    public function test_remote_absolute_url_is_treated_as_available(): void
    {
        config(['app.url' => 'http://localhost']);

        $this->assertTrue(PublicDownload::isAvailable('https://cdn.example.com/whitepaper.pdf'));
    }
}
