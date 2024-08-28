<?php

namespace CodingLibs\ZktecoPhp\Libs\Services;

use CodingLibs\ZktecoPhp\Libs\ZKTeco;

class Device
{
    /**
     * Get the name of the device.
     *
     * @param ZKTeco $self The instance of the ZKTecoPhp class.
     *
     * @return bool|mixed Returns the device name if successful, false otherwise.
     */
    public static function name(ZKTeco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~DeviceName';

        $data = $self->_command($command, $command_string);

        return Util::trimDeviceData($data, $command_string);
    }

    /**
     * Enable the device.
     *
     * @param ZKTeco $self The instance of the ZKTecoPhp class.
     *
     * @return bool|mixed Returns true if the device is enabled successfully, false otherwise.
     */
    public static function enable(ZKTeco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_ENABLE_DEVICE;
        $command_string = '';

        return $self->_command($command, $command_string);
    }

    /**
     * Disable the device.
     *
     * @param ZKTeco $self The instance of the ZKTecoPhp class.
     *
     * @return bool|mixed Returns true if the device is disabled successfully, false otherwise.
     */
    public static function disable(ZKTeco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_DISABLE_DEVICE;
        $command_string = chr(0).chr(0);

        return $self->_command($command, $command_string);
    }

    /**
     * Power off the device.
     *
     * @param ZKTeco $self The instance of the ZKTeco class.
     *
     * @return bool|mixed Returns true if the device is powered off successfully, false otherwise.
     */
    public static function powerOff(ZKTeco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_POWEROFF;
        $command_string = chr(0).chr(0);

        return $self->_command($command, $command_string);
    }

    /**
     * Restart the device.
     *
     * @param ZKTeco $self The instance of the ZKTeco class.
     *
     * @return bool|mixed Returns true if the device is restarted successfully, false otherwise.
     */
    public static function restart(ZKTeco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_RESTART;
        $command_string = chr(0).chr(0);

        return $self->_command($command, $command_string);
    }

    /**
     * Sleep the device.
     *
     * @param ZKTeco $self The instance of the ZKTeco class.
     *
     * @return bool|mixed Returns true if the device is put to sleep successfully, false otherwise.
     */
    public static function sleep(ZKTeco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_SLEEP;
        $command_string = chr(0).chr(0);

        return $self->_command($command, $command_string);
    }

    /**
     * Resume the device from sleep.
     *
     * @param ZKTeco $self The instance of the ZKTeco class.
     *
     * @return bool|mixed Returns true if the device is resumed successfully, false otherwise.
     */
    public static function resume(ZKTeco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_RESUME;
        $command_string = chr(0).chr(0);

        return $self->_command($command, $command_string);
    }

    /**
     * $voiceIndex params indexes
     *  play test voice:\n
     * 0 Thank You\n
     * 1 Incorrect Password\n
     * 2 Access Denied\n
     * 3 Invalid ID\n
     * 4 Please try again\n
     * 5 Dupicate ID\n
     * 6 The clock is flow\n
     * 7 The clock is full\n
     * 8 Duplicate finger\n
     * 9 Duplicated punch\n
     * 10 Beep kuko\n
     * 11 Beep siren\n
     * 12 -\n
     * 13 Beep bell\n
     * 14 -\n
     * 15 -\n
     * 16 -\n
     * 17 -\n
     * 18 Windows(R) opening sound\n
     * 19 -\n
     * 20 Fingerprint not emolt\n
     * 21 Password not emolt\n
     * 22 Badges not emolt\n
     * 23 Face not emolt\n
     * 24 Beep standard\n
     * 25 -\n
     * 26 -\n
     * 27 -\n
     * 28 -\n
     * 29 -\n
     * 30 Invalid user\n
     * 31 Invalid time period\n
     * 32 Invalid combination\n
     * 33 Illegal Access\n
     * 34 Disk space full\n
     * 35 Duplicate fingerprint\n
     * 36 Fingerprint not registered\n
     * 37 -\n
     * 38 -\n
     * 39 -\n
     * 40 -\n
     * 41 -\n
     * 42 -\n
     * 43 -\n
     * 43 -\n
     * 45 -\n
     * 46 -\n
     * 47 -\n
     * 48 -\n
     * 49 -\n
     * 50 -\n
     * 51 Focus eyes on the green box\n
     * 52 -\n
     * 53 -\n
     * 54 -\n
     * 55 -\n
     * Test the device's voice.
     *
     * @param ZKTeco $self       The instance of the ZKTeco class.
     * @param int    $voiceIndex
     *
     * @return bool|mixed Returns true if the device's voice test is successful, false otherwise.
     */
    public static function testVoice(ZKTeco $self, int $voiceIndex)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_TESTVOICE;
        $command_string = pack('I', $voiceIndex);

        return $self->_command($command, $command_string);
    }

    /**
     * Clear the device's LCD screen.
     *
     * @param ZKTeco $self The instance of the ZKTeco class.
     *
     * @return bool|mixed Returns true if the LCD screen is cleared successfully, false otherwise.
     */
    public static function clearLCD(ZKTeco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_CLEAR_LCD;

        return $self->_command($command, '');
    }

    /**
     * Write text into the device's LCD screen.
     *
     * @param ZKTeco $self The instance of the ZKTeco class.
     * @param int    $rank Line number of text.
     * @param string $text Text which will be displayed on the LCD screen.
     *
     * @return bool|mixed Returns true if the text is written to the LCD successfully, false otherwise.
     */
    public static function writeLCD(ZKTeco $self, $rank, $text)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_WRITE_LCD;
        $byte1 = chr((int) ($rank % 256));
        $byte2 = chr((int) ($rank >> 8));
        $byte3 = chr(0);
        $command_string = $byte1.$byte2.$byte3.' '.$text;

        return $self->_command($command, $command_string);
    }

    public static function memoryInfo(ZKTeco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_GET_FREE_SIZES;
        $data = $self->_command($command, '');

        $adminCounts = unpack('I', substr($data, 48, 4))[1];
        $userCounts = unpack('I', substr($data, 16, 4))[1];
        $userCapacity = unpack('I', substr($data, 60, 4))[1];
        $logCounts = unpack('I', substr($data, 32, 4))[1];
        $logCapacity = unpack('I', substr($data, 64, 4))[1];

        return (object) [
            'adminCounts'  => $adminCounts,
            'userCounts'   => $userCounts,
            'userCapacity' => $userCapacity,
            'logCounts'    => $logCounts,
            'logCapacity'  => $logCapacity,
        ];
    }
}
