<?php

namespace Apps\WordFrequencyCounter\Core\Validators;

use CommonF\Validators\HTTPValidatorAbstract;

class HTTPValidator extends HTTPValidatorAbstract
{
    public function validate(): bool {
        parent::validate();

        // Call Additional Custom Validations Here

        return true;
    }

    /**
     * Only Aphabet and digits not empty strings allowed
     */
    public function validateStringWord(string $word) {
        $cleanWord = filter_var(trim($word), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if ($cleanWord !== '' && ctype_alnum($cleanWord)) {
            return true;
        }

        return false;
    }

    public function getValidPageCounter(int $page) {
        return $page > 1 ? $page : 1;
    }

    public function getWrongWordMsg(string $word) {
        return "Error: Stops at wrong word \"{$word}\"";
    }

    public function getMaxStringInBytes() {
        return $this->configValidations['wordsLengthBytesAllowed'];
    }

    public function getChunkSize() {
        return $this->configValidations['chunkSize'];
    }

    public function getListsPageSize() {
        return $this->configValidations['listsPageSize'];
    }
}
