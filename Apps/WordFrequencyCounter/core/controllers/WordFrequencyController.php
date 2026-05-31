<?php

namespace Apps\WordFrequencyCounter\Core\Controllers;

use CommonF\Controllers\ControllerAbstract;
use Apps\WordFrequencyCounter\Core\Validators\HTTPValidator;
use Apps\WordFrequencyCounter\Core\Requests\Request;
use Apps\WordFrequencyCounter\Core\Entities\WordCounter;
use Apps\WordFrequencyCounter\Core\Repositories\WordsRepository;

/**
 * Request maximum allowed time is 5min
 */
set_time_limit(300);

/**
 * The code is written to handle every request without memory spikes but in any case
 * allocate all possible memory available to the server
 */
ini_set('memory_limit', '-1');


class WordFrequencyController extends ControllerAbstract
{
    protected WordsRepository $wordsRepository;
    protected array $dbConfig;

    public function __construct(Request $request, WordsRepository $wordsRepository, array $dbConfig) {
        parent::__construct($request);
        $this->wordsRepository = $wordsRepository;
        $this->dbConfig = $dbConfig;

        $this->wordsRepository->setDbConfig($this->dbConfig);
    }

    public function wordsLists() {
        $HTTPValidator = $this->getValidator(HTTPValidator::class);
        $page = $HTTPValidator->getValidPageCounter((int) $this->request->get('page', 1));

        $pagesCount = $this->wordsRepository->getPagesCount($HTTPValidator->getListsPageSize());
        $words = $this->wordsRepository->getWords($page, $HTTPValidator->getListsPageSize());

        return [
            'word_frequency_counter_list', [
                'addWordsUrl' => $this->app->urlByRouteName('wordsFrequencyCounter'),
                'viewWordUrl' => $this->app->urlByRouteName('wordsFrequencyCounterViewWord'),
                'currPage' => $page,
                'pagesCount' => $pagesCount,
                'words' => $words,
                'errorMsg' => 'Error: Cannot open input stream',
            ]
        ];
    }

    public function word() {
        $HTTPValidator = $this->getValidator(HTTPValidator::class);
        $wordId = (int) $this->request->get('wordId');

        $word = null;
        if ($wordId) {
            $word = $this->wordsRepository->getWordById((int) $wordId);
        }

        $errorMsg = empty($word) ? "Error: Word with id: \"{$this->request->get('wordId')}\" does not exist" : '';    

        return [
            'word_frequency_counter_view_word', [
                'goBackUrl' => $this->app->urlByRouteName('wordsFrequencyCounterList'),
                'errorMsg' => $errorMsg,
                'word' => $word,
            ]
        ];
    }

    public function updateWordsList() {
        return [
            'word_frequency_counter', [
                'goBackUrl' => $this->app->urlByRouteName('wordsFrequencyCounterList'),
                'submitUrl' => $this->app->urlByRouteName('wordsFrequencyCounterProcess'),
            ]
        ];
    }

    public function processUpdateWordsList() {
        $HTTPValidator = $this->getValidator(HTTPValidator::class);
        $formField = 'words';
        $stream = fopen('php://input', 'r');
        $carryOver = '';
        $errorMsg = '';

        if (!$stream) {
            return [
                'word_frequency_counter', [
                    'goBackUrl' => $this->app->urlByRouteName('wordsFrequencyCounterList'),
                    'submitUrl' => $this->app->urlByRouteName('wordsFrequencyCounterProcess'),
                    'errorMsg' => 'Error: Cannot open input stream',
                ]
            ];
        }

        $totalBytesRead = 0;
        $maxBytesAllowed = $HTTPValidator->getMaxStringInBytes();
        $chunkSize = $HTTPValidator->getChunkSize();

        while (!feof($stream)) {
            // Read the payload in chunks and append any leftovers from prev time
            $chunk = $carryOver . fread($stream, $chunkSize);
            $chunkBytesRead = strlen($chunk);
            $carryOver = '';

            if ($totalBytesRead === 0) {
                if (strlen($chunk) === 0) {
                    $errorMsg = 'Error: No Data'; break;
                }

                $chunk = substr($chunk, strlen($formField . '=')); // Cut the form key from the stream
            }

            $lastSpaceIndex = strrpos($chunk, '+'); // Cut the string before the last word beggins
            $totalBytesRead += $chunkBytesRead;

            if ($lastSpaceIndex === false && strlen($chunk) >= $chunkSize) {
                $errorMsg = 'Error: Too long words'; break;
            } else if ($totalBytesRead > $maxBytesAllowed) {
                $errorMsg = 'Error: Request blocked. Payload exceeds maximum limit';
                fclose($stream);
                break;
            }

            $totalBytesRead += $chunkBytesRead;
            if ($lastSpaceIndex > 0) {    // The chunk starts with a space
                $parts = str_split($chunk, $lastSpaceIndex);
                $carryOver = $parts[1];
                $chunk = $parts[0];
            }

            $wordsMap = [];
            $words = preg_split('/\s+/u', urldecode($chunk), -1, PREG_SPLIT_NO_EMPTY);

            foreach ($words as $word) {
                if (!$HTTPValidator->validateStringWord($word)) {
                    $errorMsg = $HTTPValidator->getWrongWordMsg($word); break 2;
                }
            }

            if (empty($words)) {
                $errorMsg = 'Error: No valid words'; break;
            } else {
                $this->wordsRepository->updateCountersForWords($words);
            }
        }

        fclose($stream);

        return [
            'word_frequency_counter', [
                'goBackUrl' => $this->app->urlByRouteName('wordsFrequencyCounterList'),
                'submitUrl' => $this->app->urlByRouteName('wordsFrequencyCounterProcess'),
                'successMsg' => empty($errorMsg) ? 'Successfully submitted but only the valid words were kept' : '',
                'errorMsg' => $errorMsg,
            ]
        ];
    }
}
