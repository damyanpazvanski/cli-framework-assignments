<?php

namespace Apps\AdvertisingBidAuction\Core\Commands;

use CommonF\Files\FileStream;
use Apps\AdvertisingBidAuction\Core\Validators\CSVValidator;
use CommonF\Commands\CommandAbstract;
use Apps\AdvertisingBidAuction\Core\Repositories\CSVFileRepository;
use Apps\AdvertisingBidAuction\Core\Files\CSVFile;
use Apps\AdvertisingBidAuction\Core\Entities\CSVRow;
use Apps\AdvertisingBidAuction\Core\Loggers\SimpleLogger;

class BestAdSecondBid extends CommandAbstract
{
    protected const CSV_HEADERS_MAP = [
        'id' => 'ad_id',
        'bid' => 'bid',
    ];

    /**
     * Define Flags
     */
    protected array $FLAGS = [
        '--continue' => false,
        '--print-warnings' => false,
    ];

    protected \NumberFormatter $numberFormatter;
    protected CSVFileRepository $CSVFileRepository;
    protected SimpleLogger $simpleLogger;
    protected string $filePath;
    protected CSVValidator $CSVValidator;

    public function __construct(CSVFileRepository $CSVFileRepository, SimpleLogger $simpleLogger, $options, $flags) {
        $this->CSVFileRepository = $CSVFileRepository;
        $this->simpleLogger = $simpleLogger;
        $this->filePath = file_exists($options[0]) ? $options[0] : __DIR__ . '/../../public/' . $options[0];
        $this->FLAGS = $this->prepareFlags($flags, $this->FLAGS);
        $this->numberFormatter = new \NumberFormatter('en_US', \NumberFormatter::DECIMAL);
    }

    public function execute(): void {
        $csv = null;

		try {
            $this->CSVFileRepository->loadStream($this->filePath, 'rb');
            $this->CSVValidator = $this->getValidator(CSVValidator::class);

            $this->numberFormatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 0);
            $this->numberFormatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $this->CSVValidator->getOptionsDecimalDigits());

            $csv = $this->CSVFileRepository->getFileStream();

            $this->CSVValidator->setFileStream($csv);

            if (!$this->CSVValidator->validate()) {
                throw new \Exception($this->CSVValidator->getInvalidFileMsg());
            }
    
            $this->csvProcess();
        } catch (\Exception $e) {
			throw $e;
		} finally {
            $csv->close();
        }
    }

    public function csvProcess() {
        $warnings = [];
        $bestBids = [
            'first' => new CSVRow(0, '0'),
            'second' => new CSVRow(0, '0'),
        ];
        $headersWithIndicesDict = $this->CSVFileRepository->getHeadersWithIndicesDict();

        foreach ($this->CSVFileRepository->getRows() as $ind => $row) {
            // Get Data Based On Header Indices
            $rowId = (int) trim($row[$headersWithIndicesDict[self::CSV_HEADERS_MAP['id']]]);
            $rowBid = trim($row[$headersWithIndicesDict[self::CSV_HEADERS_MAP['bid']]]);

            // Validate rows/values
            if ($this->CSVValidator->validateFileRowsCount($ind)) {
                throw new \Exception($this->CSVValidator->getValidateFileRowsCountMsg());
            } else if (!$this->CSVValidator->validateRowValues($row, $ind, $rowId, $rowBid, $warnings)) {
                if ($this->canContinue()) {
                    continue;
                } else {
                    throw new \Exception($warnings[0]);
                }
            }

            $CSVRow = new CSVRow($rowId, $rowBid);

            // Check Best Bids
            if (bccomp($CSVRow->bid, $bestBids['first']->bid, $this->CSVValidator->getOptionsDecimalDigits()) > 0) {
                $bestBids['second']->fillWith($bestBids['first']);
                $bestBids['first']->fillWith($CSVRow);
            } else if (bccomp($CSVRow->bid, $bestBids['second']->bid, $this->CSVValidator->getOptionsDecimalDigits()) > 0) {
                $bestBids['second']->fillWith($CSVRow);
            }
        }

        // Print Warnings if allowed
        if ($this->canPrintWarnings()) $this->printMsgsArr($warnings);

        // Success Message
        $this->simpleLogger->success($bestBids['first']->id . ', ' . $this->numberFormatter->format($bestBids['second']->bid));
    }
}
