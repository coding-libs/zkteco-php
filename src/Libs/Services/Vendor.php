<?php

namespace CodingLibs\ZktecoPhp\Libs\Services;

use CodingLibs\ZktecoPhp\Libs\ZKTeco;

class Vendor
{
    /**
     * Get the name of the vendor.
     *
     * @param ZKTeco $self The instance of the ZKTecoPhp class.
     *
     * @return bool|mixed Returns the vendor name if successful, false otherwise.
     */
    public static function name(ZKTeco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~OEMVendor';

        $data = $self->_command($command, $command_string);

        return Util::trimDeviceData($data, $command_string);
    }
}
