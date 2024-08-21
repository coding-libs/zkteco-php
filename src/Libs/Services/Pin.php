<?php

namespace CodingLibs\ZktecoPhp\Libs\Services;

use CodingLibs\ZktecoPhp\Libs\ZKTeco;

class Pin
{
    /**
     * Get the width of the PIN on the ZKTecoPhp device.
     *
     * @param ZKTeco $self The instance of the ZKTecoPhp class.
     * @return bool|mixed Returns the width of the PIN if successful, false otherwise.
     */
    static public function width(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~PIN2Width';

        return $self->_command($command, $command_string);
    }
}
