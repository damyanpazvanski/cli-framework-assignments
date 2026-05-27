<?php

namespace Apps\AdvertisingBidAuction\Core\Commands;

use Common\Files\FileStream;
use Apps\AdvertisingBidAuction\Core\Validators\CSVValidator;
use Common\Commands\CommandAbstract;
use Apps\AdvertisingBidAuction\Core\Repositories\CSVFileRepository;
use Apps\AdvertisingBidAuction\Core\Files\CSVFile;
use Apps\AdvertisingBidAuction\Core\Entities\CSVRow;

class BestAdSecondBid extends CommandAbstract
{
    protected string $filePath;
    protected CSVFileRepository $CSVFileRepository;
    protected CSVValidator $CSVValidator;

    public function __construct(CSVFileRepository $CSVFileRepository, $options, $flags) {
        $this->CSVFileRepository = $CSVFileRepository;
        $this->filePath = file_exists($options[0]) ? $options[0] : __DIR__ . '/../../public/' . $options[0];

        $this->CSVFileRepository->loadStream($this->filePath, 'rb');
    }

    public function execute(): void {
        $this->csvProcess();
    }

    public function csvProcess() {
        $csv = null;

		try {
            $csv = $this->CSVFileRepository->getFileStream();

            $CSVValidator = $this->getValidator(CSVValidator::class);
            $CSVValidator->setFileStream($csv);

            if (!$CSVValidator->validate()) {
                throw new \Exception('Invalid CSV File');
            }

            $headersWithIndicesDict = $this->CSVFileRepository->getHeadersWithIndicesDict();

			$rows = $this->CSVFileRepository->readAll();

            print_r($rows);

		} catch (\Exception $e) {
			throw $e; 
		} finally {
            $csv->close();
        }
	}
}
