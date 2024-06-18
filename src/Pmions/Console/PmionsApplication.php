<?php
declare(strict_types=1);

namespace Pmions\Console;

use Symfony\Component\Console\Application;
use Pmions\Console\Commands\Make;
use Pmions\Console\Commands\Migrate;
use Pmions\Console\Commands\RunServer;
use Pmions\Console\Commands\Seed;

class PmionsApplication extends Application
{
    public function __construct()
    {
        parent::__construct('Pmions Console Application');
        $this->add(new Make());
        $this->add(new Migrate());
        $this->add(new Seed());
        $this->add(new RunServer());
    }
}
