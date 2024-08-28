<?php

namespace CodingLibs\ZktecoPhp\Libs\Services;

use CodingLibs\ZktecoPhp\Libs\ZKTeco;

class SerialNumber
{
    /**
     * Get the serial number of the ZKTecoPhp device.
     *
     * @param ZKTeco $self The instance of the ZKTecoPhp class.
     *
     * @return bool|mixed Returns the serial number if successful, false otherwise.
     */
    public static function get(ZKTeco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~SerialNumber';

        $data = $self->_command($command, $command_string);

        return Util::trimDeviceData($data, $command_string);
    }
}
