<?php

namespace App\Command;

use App\Receiver\FileSystemManager;

class DeleteFileCommand extends AbstractCommand
{
    private FileSystemManager $fileSystemManager;
    private string $filename;
    private string $backupContent;

    public function __construct(
        FileSystemManager $fileSystemManager,
        string $filename
    ) {
        parent::__construct(
            'Delete File',
            "Delete file: $filename",
            ['filename' => $filename]
        );
        
        $this->fileSystemManager = $fileSystemManager;
        $this->filename = $filename;
        $this->backupContent = '';
    }

    /**
     * Execute the command
     *
     * @return mixed
     */
    public function execute()
    {
        // Backup the file content before deleting
        $this->backupContent = $this->fileSystemManager->readFile($this->filename);
        $result = $this->fileSystemManager->deleteFile($this->filename);
        $this->markAsExecuted();
        return $result;
    }

    /**
     * Undo the command
     *
     * @return mixed
     */
    public function undo()
    {
        $result = $this->fileSystemManager->createFile($this->filename, $this->backupContent);
        $this->markAsUndone();
        return $result;
    }
}