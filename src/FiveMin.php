<?php

namespace FiveMin;

use League\Csv\Reader as CsvReader;
use League\Csv\Writer as CsvWriter;

class FiveMin
{

    /**
     * Length of the interval in minutes.
     *
     * @var int
     */
    protected $intervalLength = 5;

    /**
     * Set the new interval length.
     *
     * @param int $intervalLength
     * @return FiveMin
     */
    public function intervalLength(int $intervalLength) : FiveMin
    {
        $this->intervalLength = $intervalLength;
        return $this;
    }

    /**
     * Reads a CSV file and removes the columns that are not in the interval.
     *
     * @param string $fileLocation
     * @param string $dateTimeHeaderName
     * @param string|null $saveFileName
     * @param string|null $saveDirectory
     * @throws \ErrorException
     */
    public function go(
        string $fileLocation,
        string $dateTimeHeaderName,
        string $saveFileName = null,
        string $saveDirectory = null
    ) : void {
        if(! file_exists($fileLocation)) {
            throw new \ErrorException("File '$fileLocation' does not exist.");
        }

        echo "Processing file \"$fileLocation\". Looking for the header \"$dateTimeHeaderName\". Please wait.\n";
        $csvContent = $this->removeNotInInterval($fileLocation, $dateTimeHeaderName);
        $csvFileName = basename($fileLocation) . '.csv';
        if(! $saveFileName) {
            $saveFileName = $csvFileName;
        }
        $savePath = $this->filePath($saveFileName, $saveDirectory);
        touch($savePath);
        echo "Saving to file \"$savePath\".\n";
        $new = CsvWriter::createFromPath($savePath);
        $new->insertAll($csvContent);
        echo "Done.";
    }

    /**
     * Create the full file path.
     *
     * @param string $fileName
     * @param string|null $directory
     * @return string
     */
    protected function filePath(string $fileName, string $directory = null) : string
    {
        if(! $directory) {
            // Save on the project root directory
            $directory = realpath(__DIR__ . '/..');
        }

        return $directory . DIRECTORY_SEPARATOR . $fileName;
    }

    /**
     * Removes values that are not within the interval and returns it.
     *
     * @param string $fileLocation
     * @param string $headerName The name of the datetime header
     * @return array|bool
     */
    protected function removeNotInInterval(string $fileLocation, string $headerName) : array
    {
        $csvContent = $this->readCsvContent($fileLocation);
        if(! $csvContent) {
            return false;
        }

        $removed = [];
        foreach($csvContent as $element => $content) {
            if($this->isIntervalOf($content[$headerName])) {
                $removed[] = $content;
            }
        }

        return $removed;
    }

    /**
     * Determine if datetime is within the set interval.
     *
     * @param string $dateTime
     * @return bool
     */
    protected function isIntervalOf(string $dateTime) : bool
    {
        return date('i', strtotime($dateTime)) % $this->intervalLength === 0;
    }

    /**
     * Read the CSV file content and cast it into an Iterator.
     *
     * @param string $fileLocation
     * @return bool|\Iterator
     */
    protected function readCsvContent(string $fileLocation) : \Iterator
    {
        $csv = CsvReader::createFromPath($fileLocation);
        $results = $csv->fetchAssoc();

        if(empty($results) || ! $results) {
            return false;
        }
        return $results;
    }

}
