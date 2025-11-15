<?php

namespace App\Command;

use App\Receiver\FileSystemManager;

class CreateFileCommand extends AbstractCommand
{
    private FileSystemManager $fileSystemManager;
    private string $filename;
    private string $content;

    public function __construct(
        FileSystemManager $fileSystemManager,
        string $filename,
        string $content = ''
    ) {
        parent::__construct(
            'Create File',
            "Create file: $filename",
            ['filename' => $filename]
        );
        
        $this->fileSystemManager = $fileSystemManager;
        $this->filename = $filename;
        $this->content = $content;
    }

    /**
     * Execute the command
     *
     * @return mixed
     */
    public function execute()
    {
        $result = $this->fileSystemManager->createFile($this->filename, $this->content);
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
        $result = $this->fileSystemManager->deleteFile($this->filename);
        $this->markAsUndone();
        return $result;
    }
}