<?php

namespace CodingLibs\ZktecoPhp\Libs\Services;

use CodingLibs\ZktecoPhp\Exceptions\PingException;
use CodingLibs\ZktecoPhp\Libs\ZKTeco;

class Ping
{
    public static function run(ZKTeco $self, $throw = false)
    {
        if (!$self->_requiredPing) {
            return true;
        }

        $newThrow = !$self->_silentPing;

        if ($throw) {
            $newThrow = $throw;
        }

        $ip = $self->_ip;

        // Determine the operating system
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        // Ping parameters as function of OS
        $pingCommand = $isWindows ? 'ping -n 1 '.escapeshellarg($ip) : 'ping -c 1 -W 5 '.escapeshellarg($ip);

        // Execute the ping command
        $output = null;
        $resultCode = null;

        exec($pingCommand, $output, $resultCode);

        // Return true if ping was successful (result code is 0)
        $result = $resultCode === 0;

        if (!$result && $newThrow) {
            throw new PingException("can't reach device ($ip)");
        }

        return $result;
    }
}
