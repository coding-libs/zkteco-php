<?php

namespace CodingLibs\ZktecoPhp\Libs\Services;

use CodingLibs\ZktecoPhp\Libs\ZKTeco;

class Ssr
{
    /**
     * Get information about SSR (Self-Service Recorder) on the ZKTecoPhp device.
     *
     * @param ZKTeco $self The instance of the ZKTecoPhp class.
     * @return bool|mixed Returns SSR information if successful, false otherwise.
     */
    static public function get(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~SSR';
        $command_string = '~';

        return $self->_command($command, $command_string);
    }
}
