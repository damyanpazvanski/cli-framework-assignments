<?php

namespace Common\Validators;

use Common\Files\FileStream;
use Common\Interfaces\IValidator;

abstract class FileValidatorAbstract implements IValidator
{
    protected FileStream $fileStream;
    protected array $configValidations;
    protected array $requiredColumns;

    public function __construct(array $configValidations) {
        $this->configValidations = $configValidations;
	}

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
