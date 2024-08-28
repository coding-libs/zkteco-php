<?php

namespace CodingLibs\ZktecoPhp\Libs\Services;

use CodingLibs\ZktecoPhp\Libs\Zkteco;

class WorkCode
{
    /**
     * Retrieves work codes configured on the ZKTeco device.
     *
     * This method sends a command to the ZKTeco device requesting the list of configured work codes.
     * The response may contain information about each work code, depending on the device model.
     *
     * @param Zkteco $self An instance of the ZKTeco class.
     *
     * @return bool|mixed The work code data retrieved from the device on success, false on failure.
     *                    The exact format of the data depends on the device model.
     */
    public static function get(Zkteco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__; // Set the current section for internal tracking (optional)

        $command = Util::CMD_DEVICE; // Device information command code
        $command_string = 'WorkCode'; // Specific data request: Work Code information

        $data = $self->_command($command, $command_string);

        return Util::trimDeviceData($data, $command_string);
    }
}
