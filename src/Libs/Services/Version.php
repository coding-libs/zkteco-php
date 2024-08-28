<?php

namespace CodingLibs\ZktecoPhp\Libs\Services;

use CodingLibs\ZktecoPhp\Libs\Zkteco;

class Version
{
    /**
     * Retrieves the ZKTeco device version information.
     *
     * This method sends a version command to the ZKTeco device and retrieves the response containing
     * the device's firmware version.
     *
     * @param Zkteco $self An instance of the ZKTeco class.
     *
     * @return bool|mixed The device version string on success, false on failure.
     */
    public static function get(Zkteco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__; // Set the current section for internal tracking (optional)

        $command = Util::CMD_VERSION; // Version information command code
        $command_string = ''; // Empty command string (no additional data needed)

        $data = $self->_command($command, $command_string); // Use internal ZKTeco method to send the command

        return trim($data);
    }
}
