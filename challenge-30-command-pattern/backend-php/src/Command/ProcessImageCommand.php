<?php

namespace App\Command;

use App\Receiver\ImageProcessor;

class ProcessImageCommand extends AbstractCommand
{
    private ImageProcessor $imageProcessor;
    private string $sourcePath;
    private string $destinationPath;
    private array $operations;
    private string $originalContent;

    public function __construct(
        ImageProcessor $imageProcessor,
        string $sourcePath,
        string $destinationPath,
        array $operations = []
    ) {
        parent::__construct(
            'Process Image',
            "Process image: $sourcePath",
            [
                'source' => $sourcePath,
                'destination' => $destinationPath,
                'operations' => $operations
            ]
        );
        
        $this->imageProcessor = $imageProcessor;
        $this->sourcePath = $sourcePath;
        $this->destinationPath = $destinationPath;
        $this->operations = $operations;
        $this->originalContent = '';
    }

    /**
     * Execute the command
     *
     * @return mixed
     */
    public function execute()
    {
        // Backup original file if it exists
        if (file_exists($this->destinationPath)) {
            $this->originalContent = file_get_contents($this->destinationPath);
        }
        
        $result = $this->imageProcessor->process($this->sourcePath, $this->destinationPath, $this->operations);
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
        if (!empty($this->originalContent)) {
            // Restore original file content
            file_put_contents($this->destinationPath, $this->originalContent);
        } else {
            // If there was no original file, delete the processed file
            if (file_exists($this->destinationPath)) {
                unlink($this->destinationPath);
            }
        }
        
        $this->markAsUndone();
        return true;
    }
}