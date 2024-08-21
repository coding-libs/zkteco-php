<?php

namespace CodingLibs\ZktecoPhp\Libs\Services;

use CodingLibs\ZktecoPhp\Libs\ZKTeco;

class Face
{
    /**
     * Turn on the face recognition feature of the device.
     *
     * @param ZKTeco $self The instance of the ZKTecoPhp class.
     * @return bool|mixed Returns true if the face recognition feature is turned on successfully, false otherwise.
     */
    static public function on(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = 'FaceFunOn';

        return $self->_command($command, $command_string);
    }
}
