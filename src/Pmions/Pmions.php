<?php

namespace Pmions;

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Pmions\Console\PmionsApplication;

$pmions = new PmionsApplication();
$output = new ConsoleOutput();

$pmions->doRun(new ArgvInput(), $output);
