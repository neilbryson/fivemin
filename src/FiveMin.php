<?php

namespace FiveMin;

use League\Csv\Reader as CsvReader;
use League\Csv\Writer as CsvWriter;

class FiveMin
{

    /**
     * @var int
     */
    protected $intervalStart = 0;

    /**
     * @var int
     */
    protected $intervalEnd = 55;

    /**
     * Length of the interval in minutes.
     *
     * @var int
     */
    protected $intervalLength = 5;

    /**
     * Reads a CSV file and removes the columns that are not in the interval.
     *
     * @param $fileLocation
     * @param $dateTimeHeaderName
     * @throws \ErrorException
     */
    public function go($fileLocation, $dateTimeHeaderName)
    {
        if(! file_exists($fileLocation)) {
            throw new \ErrorException("File '$fileLocation' does not exist.");
        }
        $csvContent = $this->removeNotInInterval($fileLocation, $dateTimeHeaderName);
        touch('hoy.csv');
        $new = CsvWriter::createFromPath('hoy.csv');
        $new->insertAll($csvContent);
    }

    /**
     * Removes values that are not within the interval and returns it.
     *
     * @param string $fileLocation
     * @param string $headerName The name of the datetime header
     * @return array|bool
     */
    protected function removeNotInInterval($fileLocation, $headerName)
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
    protected function isIntervalOf($dateTime)
    {
        return date('i', strtotime($dateTime)) % $this->intervalLength === 0;
    }

    /**
     * Read the CSV file content and cast it into an Iterator.
     *
     * @param string $fileLocation
     * @return bool|\Iterator
     */
    protected function readCsvContent($fileLocation)
    {
        $csv = CsvReader::createFromPath($fileLocation);
        $results = $csv->fetchAssoc();
        if(empty($results) || ! $results) {
            return false;
        }
        return $results;
    }

}