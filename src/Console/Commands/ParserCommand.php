<?php

namespace FiveMin\Console\Commands;

use FiveMin\FiveMin;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;

class ParserCommand extends Command
{

    protected $fileLocationKey = 'file_location';

    protected $fileLocationShortKey = 'f';

    protected $headerKey = 'date_time_header_name';

    protected $headerShortKey = 'd';

    protected function configure()
    {
        $this->setName('go')
            ->setDescription('Removes rows between the five minute interval')
            ->setDefinition(
                new InputDefinition([
                    new InputArgument(
                        $this->fileLocationKey,
                        null,
                        "The location of the CSV file"
                    ),
                    new InputArgument(
                        $this->headerKey,
                        null,
                        "The name of the datetime header on the CSV file"
                    )
                ])
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileLocation = $input->getArgument($this->fileLocationKey);
        $dateTimeHeader = $input->getArgument($this->headerKey);
        $f = new FiveMin();
        $f->go($fileLocation, $dateTimeHeader);
    }

}