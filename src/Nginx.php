<?php

namespace Turbo124\Waffy;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Nginx
{

    public static function test(string $nginx_path = '/usr/sbin/nginx'): bool
    {
                
        $process = new Process(['sudo', $nginx_path, '-t']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return true;

    }

    public static function reload(string $nginx_path = '/usr/sbin/nginx'): bool
    {
            
        self::test();

        // If test passed, reload
        $process = new Process(['sudo', $nginx_path, '-s', 'reload']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return true;

    }
}