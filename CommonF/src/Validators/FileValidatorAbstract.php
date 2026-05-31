<?php

namespace CommonF\Validators;

use CommonF\Files\FileStream;
use CommonF\Validators\ValidatorAbstract;

abstract class FileValidatorAbstract extends ValidatorAbstract
{
    protected FileStream $fileStream;
    protected array $requiredColumns;

    protected function validateFile(): void{
        if (!$this->fileStream->getStream()) {
            throw new \Exception("Validation Error: File does not exist at '$path'");
        }
        
        $metaData = stream_get_meta_data($this->fileStream->getStream());

        if (pathinfo($metaData['uri'], PATHINFO_EXTENSION) !== $this->configValidations['ext']) {
            throw new \Exception("Validation Error: File must be a ." . $this->configValidations['ext']);
        }

        if (filesize($metaData['uri']) === 0) {
            throw new \Exception("Validation Error: File is empty");
        }
    }

    protected function validateHeaders(): void {
        $missing = array_diff($this->configValidations['headers'], $this->fileStream->getHeaders());

        if (!empty($missing)) {
            throw new \Exception("Validation Error: Missing required columns: " . implode(', ', $missing));
        }
    }

    protected function validateDataSize(): void {
        $stats = fstat($this->fileStream->getStream());

        if ($stats === false || ( $this->configValidations['maxByteSize'] && $stats['size'] > $this->configValidations['maxByteSize'])) {
            throw new \Exception('File is too large');
        }
    }

    public function setFileStream(FileStream $fileStream) {
        $this->fileStream = $fileStream;
    }

    public function getOptions(): array {
        return $this->configValidations['options'] ?? [];
    }

    public function validate(): bool {
        $this->validateFileStream();
        $this->validateFile();
        $this->validateDataSize();
        $this->validateHeaders();

        return true;
    }

    protected function validateFileStream(): void {
        if (!$this->fileStream) {
            throw new \Exception('File Stream does not exist');
        }
    }
}
