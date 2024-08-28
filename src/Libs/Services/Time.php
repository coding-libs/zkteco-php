<?php

namespace CodingLibs\ZktecoPhp\Libs\Services;

use CodingLibs\ZktecoPhp\Libs\ZKTeco;

class Time
{
    /**
     * Set the time on the ZKTecoPhp device.
     *
     * @param ZKTeco $self The instance of the ZKTecoPhp class.
     * @param string $t    The time to set in the format "Y-m-d H:i:s".
     *
     * @return bool|mixed Returns true if the time is set successfully, false otherwise.
     */
    public static function set(ZKTeco $self, $t)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_SET_TIME;
        $command_string = pack('I', Util::encodeTime($t));

        return $self->_command($command, $command_string);
    }

    /**
     * Get the current time from the ZKTecoPhp device.
     *
     * @param ZKTeco $self The instance of the ZKTecoPhp class.
     *
     * @return bool|mixed Returns the current time if successful, false otherwise.
     */
    public static function get(ZKTeco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_GET_TIME;
        $command_string = '';

        $ret = $self->_command($command, $command_string);

        if ($ret) {
            return Util::decodeTime(hexdec(Util::reverseHex(bin2hex($ret))));
        } else {
            return false;
        }
    }
}
