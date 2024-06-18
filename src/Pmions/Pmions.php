<?php

namespace Pmions;

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Pmions\Console\PmionsApplication;

class Pmions
{
    public static function console()
    {
        $pmions = new PmionsApplication();
        $output = new ConsoleOutput();
        
        $pmions->doRun(new ArgvInput(), $output);
    }
}


