<?php

namespace CodingLibs\ZktecoPhp\Libs;

use CodingLibs\ZktecoPhp\Libs\Services\Attendance;
use CodingLibs\ZktecoPhp\Libs\Services\Connect;
use CodingLibs\ZktecoPhp\Libs\Services\Device;
use CodingLibs\ZktecoPhp\Libs\Services\Face;
use CodingLibs\ZktecoPhp\Libs\Services\Fingerprint;
use CodingLibs\ZktecoPhp\Libs\Services\Os;
use CodingLibs\ZktecoPhp\Libs\Services\Pin;
use CodingLibs\ZktecoPhp\Libs\Services\Ping;
use CodingLibs\ZktecoPhp\Libs\Services\Platform;
use CodingLibs\ZktecoPhp\Libs\Services\SerialNumber;
use CodingLibs\ZktecoPhp\Libs\Services\Ssr;
use CodingLibs\ZktecoPhp\Libs\Services\Time;
use CodingLibs\ZktecoPhp\Libs\Services\User;
use CodingLibs\ZktecoPhp\Libs\Services\Util;
use CodingLibs\ZktecoPhp\Libs\Services\Vendor;
use CodingLibs\ZktecoPhp\Libs\Services\Version;
use CodingLibs\ZktecoPhp\Libs\Services\WorkCode;
use ErrorException;
use Exception;

class ZKTeco
{
    public $_ip;
    public $_port;
    public $_zkclient;
    public $_data_recv = '';
    public $_session_id = 0;
    public $_section = '';
    public $_requiredPing = false;
    public $_silentPing = false;

    /**
     * @param string $ip         Device IP address.
     * @param int    $port       Port number. Default: 4370.
     * @param bool   $shouldPing should ping before device connection
     * @param int    $timeout    timeout in sec
     */
    public function __construct(string $ip, int $port = 4370, bool $shouldPing = false, int $timeout = 25)
    {
        $this->_ip = $ip;
        $this->_port = $port;
        $this->_requiredPing = (bool) $shouldPing;

        $this->_zkclient = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

        $timeout = ['sec' => $timeout, 'usec' => 500000];
        socket_set_option($this->_zkclient, SOL_SOCKET, SO_RCVTIMEO, $timeout);
    }

    /**
     * Overwrite ping setup.
     *
     * @param bool $shouldPing
     * @param bool $silentPing
     *
     * @return void
     */
    public function setPing(bool $shouldPing = false, bool $silentPing = true): void
    {
        $this->_silentPing = (bool) $silentPing;
        $this->_requiredPing = (bool) $shouldPing;
    }

    /**
     * Create and send command to device.
     *
     * @param string $command
     * @param string $command_string
     * @param string $type
     *
     * @return bool|mixed
     */
    public function _command(string $command, string $command_string, string $type = Util::COMMAND_TYPE_GENERAL)
    {
        $chksum = 0;
        $session_id = $this->_session_id;

        $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6/H2h7/H2h8', substr($this->_data_recv, 0, 8));
        $reply_id = hexdec($u['h8'].$u['h7']);

        $buf = Util::createHeader($command, $chksum, $session_id, $reply_id, $command_string);

        socket_sendto($this->_zkclient, $buf, strlen($buf), 0, $this->_ip, $this->_port);

        try {
            @socket_recvfrom($this->_zkclient, $this->_data_recv, 1024, 0, $this->_ip, $this->_port);

            $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6', substr($this->_data_recv, 0, 8));

            $ret = false;
            $session = hexdec($u['h6'].$u['h5']);

            if ($type === Util::COMMAND_TYPE_GENERAL && $session_id === $session) {
                $ret = substr($this->_data_recv, 8);
            } elseif ($type === Util::COMMAND_TYPE_DATA && !empty($session)) {
                $ret = $session;
            }

            return $ret;
        } catch (ErrorException|Exception $e) {
            return false;
        }
    }

    /**
     * Connects to the device.
     *
     * @return bool True if successfully connected, otherwise false.
     */
    public function connect(): bool
    {
        return Connect::connect($this);
    }

    /**
     * Disconnects from the device.
     *
     * @return bool True if successfully disconnected, otherwise false.
     */
    public function disconnect(): bool
    {
        return Connect::disconnect($this);
    }

    /**
     * Retrieves the version information of the device.
     *
     * @return bool|mixed The version information of the device.
     */
    public function version()
    {
        return Version::get($this);
    }

    /**
     * Retrieves the operating system (OS) version from the device.
     *
     * @return bool|mixed The OS version from the device.
     */
    public function osVersion()
    {
        return Os::get($this);
    }

    /**
     * Retrieves the platform information from the device.
     *
     * @return bool|mixed The platform information from the device.
     */
    public function platform()
    {
        return Platform::get($this);
    }

    /**
     * Retrieves the firmware version of the device.
     *
     * @return bool|mixed The firmware version of the device.
     */
    public function fmVersion()
    {
        return Platform::getVersion($this);
    }

    /**
     * Retrieves the work code from the device.
     *
     * @return bool|mixed The work code from the device.
     */
    public function workCode()
    {
        return WorkCode::get($this);
    }

    /**
     * Retrieves the SSR (Self-Service Recorder) information from the device.
     *
     * @return bool|mixed The SSR information from the device.
     */
    public function ssr()
    {
        return Ssr::get($this);
    }

    /**
     * Retrieves the pin width of the device.
     *
     * @return bool|mixed The pin width of the device.
     */
    public function pinWidth()
    {
        return Pin::width($this);
    }

    /**
     * Enables the face recognition function on the device.
     *
     * @return bool|mixed True if the face recognition function was successfully enabled.
     */
    public function faceFunctionOn()
    {
        return Face::on($this);
    }

    /**
     * Retrieves the serial number of the device.
     *
     * @return bool|mixed The serial number of the device.
     */
    public function serialNumber()
    {
        return SerialNumber::get($this);
    }

    /**
     * Retrieves the name of the vendor.
     *
     * @return bool|mixed The name of the vendor.
     */
    public function vendorName()
    {
        return Vendor::name($this);
    }

    /**
     * Retrieves the name of the device.
     *
     * @return bool|mixed The name of the device.
     */
    public function deviceName()
    {
        return Device::name($this);
    }

    /**
     * Disables the device.
     *
     * @return bool|mixed True if the device was successfully disabled.
     */
    public function disableDevice()
    {
        return Device::disable($this);
    }

    /**
     * Enables the device.
     *
     * @return bool|mixed True if the device was successfully enabled.
     */
    public function enableDevice()
    {
        return Device::enable($this);
    }

    /**
     * Retrieves user data from the device.
     *
     * @return array An array containing user data.
     */
    public function getUsers(callable $callback = null): array
    {
        return User::get($this, $callback);
    }

    /**
     * Sets user data for the specified user.
     *
     * @param int        $uid      Unique ID of the user.
     * @param int|string $userid   ID in DB.
     * @param string     $name     Name of the user.
     * @param int|string $password Password for the user.
     * @param int        $role     Role of the user.
     * @param int        $cardno   Card number associated with the user.
     *
     * @return bool|mixed True if user data was successfully set.
     */
    public function setUser(int $uid, $userid, string $name, $password, int $role = Util::LEVEL_USER, int $cardno = 0)
    {
        return User::set($this, $uid, $userid, $name, $password, $role, $cardno);
    }

    /**
     * Removes all users from the device.
     *
     * @return bool|mixed True if all users were successfully removed.
     */
    public function clearAllUsers()
    {
        return User::clearAll($this);
    }

    /**
     * Removes users from the device.
     *
     * @return bool|mixed True if all users were successfully removed.
     */
    public function deleteUsers(callable $callback)
    {
        return User::deleteUsers($this, $callback);
    }

    /**
     * Removes the admin privileges from the current user.
     *
     * @return bool|mixed True if the admin privileges were successfully removed.
     */
    public function clearAdminPriv()
    {
        return User::clearAdminPriv($this);
    }

    /**
     * Removes a user identified by the specified UID from the device.
     *
     * @param int $uid The unique ID of the user to be removed.
     *
     * @return bool|mixed True if the user was successfully removed.
     */
    public function removeUser(int $uid)
    {
        return User::remove($this, $uid);
    }

    /**
     * Sets a fingerprint for a specified user on the device.
     *
     * @param int   $uid         Unique ID of the user.
     * @param array $fingerprint Array of fingerprint binary data.
     *
     * @return bool|mixed True if fingerprint data was successfully set.
     */
    public function getFingerprint(int $uid)
    {
        return Fingerprint::get($this, $uid);
    }

    /**
     * Sets a fingerprint for a specified user on the device.
     *
     * @param int   $uid         Unique ID of the user.
     * @param array $fingerprint Array of fingerprint binary data.
     *
     * @return bool|mixed True if fingerprint data was successfully set.
     */
    public function setFingerprint(int $uid, array $fingerprint)
    {
        return Fingerprint::set($this, $uid, $fingerprint);
    }

    /**
     * Removes fingerprints associated with the specified UID and fingers ID array from the device.
     *
     * @param int   $uid  Unique ID (max 65535) of the user whose fingerprints will be removed.
     * @param array $data Array containing the fingers ID (0-9) of the fingerprints to be removed.
     *
     * @return int The count of deleted fingerprints.
     */
    public function removeFingerprint($uid, array $data)
    {
        return Fingerprint::remove($this, $uid, $data);
    }

    /**
     * Retrieves the attendance records from the device.
     *
     * @return array An array containing attendance records.
     */
    public function getAttendances(callable $callback = null): array
    {
        return Attendance::get($this, $callback);
    }

    /**
     * Clears the attendance log of the device.
     *
     * @return bool|mixed True if the attendance log was successfully cleared, otherwise returns the result from Attendance::clear.
     */
    public function clearAttendance()
    {
        return Attendance::clear($this);
    }

    /**
     * Sets the device time to the specified value.
     *
     * @param string $t The time to set, in the format "Y-m-d H:i:s".
     *
     * @return bool|mixed True if the device time was successfully set, otherwise returns the result from Time::set.
     */
    public function setTime($t)
    {
        return Time::set($this, $t);
    }

    /**
     * Retrieves the current time from the device.
     *
     * @return bool|mixed The current time in the format "Y-m-d H:i:s", or the result from Time::get.
     */
    public function getTime()
    {
        return Time::get($this);
    }

    /**
     * Shuts down the device.
     *
     * @return bool|mixed True if the device was successfully shut down, otherwise returns the result from Device::powerOff.
     */
    public function shutdown()
    {
        return Device::powerOff($this);
    }

    /**
     * Restarts the device.
     *
     * @return bool|mixed True if the device restarted successfully, otherwise returns the result from Device::restart.
     */
    public function restart()
    {
        return Device::restart($this);
    }

    /**
     * Puts the device into sleep mode.
     *
     * @return bool|mixed True if the device entered sleep mode successfully, otherwise returns the result from Device::sleep.
     */
    public function sleep()
    {
        return Device::sleep($this);
    }

    /**
     * Resumes the device from sleep mode.
     *
     * @return bool|mixed True if the device was successfully resumed, otherwise returns the result from Device::resume.
     */
    public function resume()
    {
        return Device::resume($this);
    }

    /**
     * Performs a voice test by producing the sound "Thank you".
     *
     * @return bool|mixed True if the voice test was successful, otherwise returns the result from Device::testVoice.
     */
    public function testVoice($index = 0)
    {
        return Device::testVoice($this, $index);
    }

    /**
     * Clears the content displayed on the LCD screen.
     *
     * @return bool True if the content was successfully cleared, false otherwise.
     */
    public function clearLCD()
    {
        return Device::clearLCD($this);
    }

    /**
     * Writes a welcome message to the LCD screen.
     *
     * @return bool True if the message was successfully written, false otherwise.
     */
    public function writeLCD($message = 'Welcome ZkTeco')
    {
        return Device::writeLCD($this, 2, $message);
    }

    /**
     * Memory Info from the device.
     *
     * @return bool|string Captured memory data
     */
    public function getMemoryInfo()
    {
        return Device::memoryInfo($this);
    }

    /**
     * Memory Info from the device.
     *
     * @return bool|string Captured ip existence.
     */
    public function ping($throw = false)
    {
        return Ping::run($this, $throw);
    }
}
