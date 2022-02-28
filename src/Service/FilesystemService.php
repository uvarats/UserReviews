<?php

namespace App\Service;

use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;

class FilesystemService
{
    private Filesystem $filesystem;
    private DropboxAdapter $adapter;
    public function __construct(string $dropboxAccessToken)
    {
        $client = new Client($dropboxAccessToken);
        $this->adapter = new DropboxAdapter($client);
        $this->filesystem = new Filesystem($this->adapter, ['case_sensitive' => false]);
    }
    public function getFilesystem() : ?Filesystem{
        return $this->filesystem;
    }

    /**
     * @return DropboxAdapter
     */
    public function getAdapter(): DropboxAdapter
    {
        return $this->adapter;
    }
}