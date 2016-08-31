<?php

namespace Droid\Lib\Plugin\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Add a --check mode to commands using this trait.
 *
 * Commands should call configureCheckMode to add the command option,
 * activateCheckMode when the command is executed, markChange when the command
 * would make a change and finally reportChange when the command is finished
 * executing.
 */
trait CheckableTrait
{
    private $check = false;
    private $change = false;
    private $start;
    private $end;

    /**
     * See whether the command is in check mode.
     *
     * @return boolean
     */
    public function checkMode()
    {
        return $this->check === true;
    }

    /**
     * See whether the command would make a change.
     *
     * @return boolean
     */
    public function wouldChange()
    {
        return $this->change === true;
    }

    /**
     * Add a command option allowing the check feature to be activated.
     */
    private function configureCheckMode()
    {
        $this->addOption(
            'check',
            'c',
            InputOption::VALUE_NONE,
            'Do not actually make any changes, but report whether or not a change would be made.'
        );
    }

    /**
     * Activate check mode, but only if the check option was given and note the
     * execution start time.
     *
     * @param InputInterface $input
     */
    private function activateCheckMode(InputInterface $input)
    {
        $this->check = $input->getOption('check');
        $this->start = microtime(true);
    }

    /**
     * Mark the command as having the intention to make a change.
     */
    private function markChange()
    {
        $this->change = true;
    }

    /**
     * Write the result of the check to the supplied OutputInterface.
     *
     * @param OutputInterface $output
     */
    private function reportChange(OutputInterface $output)
    {
        $this->end = microtime(true);

        $output->writeLn(
            sprintf('[DROID-RESULT] %s', json_encode(array(
                'changed' => $this->change,
                'start' => $this->start,
                'end' => $this->end,
            )))
        );
    }
}
