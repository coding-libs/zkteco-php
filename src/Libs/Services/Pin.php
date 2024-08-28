<?php

namespace CodingLibs\ZktecoPhp\Libs\Services;

use CodingLibs\ZktecoPhp\Libs\ZKTeco;

class Pin
{
    /**
     * Get the width of the PIN on the ZKTecoPhp device.
     *
     * @param ZKTeco $self The instance of the ZKTecoPhp class.
     *
     * @return bool|mixed Returns the width of the PIN if successful, false otherwise.
     */
    public static function width(ZKTeco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~PIN2Width';

        $data = $self->_command($command, $command_string);

        return Util::trimDeviceData($data, $command_string);
    }
}
