<?php

namespace CodingLibs\ZktecoPhp\Libs\Services;

class Ping
{

    static public function testPing($ip)
    {
        // Determine the operating system
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        // Ping parameters as function of OS
        $pingCommand = $isWindows ? "ping -n 1 " . escapeshellarg($ip) : "ping -c 1 -W 5 " . escapeshellarg($ip);

        // Execute the ping command
        $output = null;
        $resultCode = null;

        exec($pingCommand, $output, $resultCode);

        // Return true if ping was successful (result code is 0)
        return $resultCode === 0;
    }
}
