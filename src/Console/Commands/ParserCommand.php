<?php

namespace FiveMin\Console\Commands;

use FiveMin\FiveMin;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ParserCommand extends Command
{

    /**
     * @var string
     */
    protected $fileLocationKey = 'file-location';

    /**
     * @var string
     */
    protected $fileLocationShortKey = 'f';

    /**
     * @var string
     */
    protected $headerKey = 'date-time-header-name';

    /**
     * @var string
     */
    protected $headerShortKey = 'h';

    /**
     * @var string
     */
    protected $saveDirectoryKey = 'save-dir';

    /**
     * @var string
     */
    protected $saveDirectoryShortKey = 'd';

    /**
     * @var string
     */
    protected $saveFileNameKey = 'save-file-name';

    /**
     * @var string
     */
    protected $saveFileNameShortKey = 'name';

    /**
     * @var string
     */
    protected $intervalLengthKey = 'interval';

    /**
     * @var string
     */
    protected $intervalLengthShortKey = 'i';

    /**
     * Configure the command arguments.
     *
     * @return void
     */
    protected function configure() : void
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
                    ),
                    new InputOption(
                        $this->saveDirectoryKey,
                        $this->saveDirectoryShortKey,
                        InputOption::VALUE_OPTIONAL,
                        "Location on where to save the CSV file"
                    ),
                    new InputOption(
                        $this->saveFileNameKey,
                        $this->saveFileNameShortKey,
                        InputOption::VALUE_OPTIONAL,
                        "The name of the new file"
                    ),
                    new InputOption(
                        $this->intervalLengthKey,
                        $this->intervalLengthShortKey,
                        InputOption::VALUE_OPTIONAL,
                        "The interval length"
                    )
                ])
            );
    }

    /**
     * Execute the command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        $fileLocation = $input->getArgument($this->fileLocationKey);
        $dateTimeHeader = $input->getArgument($this->headerKey);
        $saveFileName = $input->getOption($this->saveFileNameKey);
        $saveDirectory = $input->getOption($this->saveDirectoryKey);
        $intervalLength = $input->getOption($this->intervalLengthKey);
        $f = new FiveMin();
        if($intervalLength) {
            $f->intervalLength((int)$intervalLength);
            echo "Set interval length to $intervalLength.\n";
        }
        $f->go($fileLocation, $dateTimeHeader, $saveFileName, $saveDirectory);
    }

}
