<?php

namespace CodingLibs\ZktecoPhp\Libs\Services;

use CodingLibs\ZktecoPhp\Libs\ZKTeco;

class Platform
{
    /**
     * Get the platform information of the ZKTecoPhp device.
     *
     * @param ZKTeco $self The instance of the ZKTecoPhp class.
     *
     * @return bool|mixed Returns the platform information if successful, false otherwise.
     */
    public static function get(ZKTeco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~Platform';

        $data = $self->_command($command, $command_string);

        return Util::trimDeviceData($data, $command_string);
    }

    /**
     * Get the version of the platform on the ZKTecoPhp device.
     *
     * @param ZKTeco $self The instance of the ZKTeco class.
     *
     * @return bool|mixed Returns the platform version if successful, false otherwise.
     */
    public static function getVersion(ZKTeco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~ZKFPVersion';

        $data = $self->_command($command, $command_string);

        return Util::trimDeviceData($data, $command_string);
    }
}
