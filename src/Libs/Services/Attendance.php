<?php

namespace CodingLibs\ZktecoPhp\Libs\Services;

use CodingLibs\ZktecoPhp\Libs\ZKTeco;

class Attendance
{
    /**
     * Fetches attendance data from the ZKTecoPhp device.
     *
     * This method retrieves attendance records from the connected ZKTecoPhp device.
     *
     * @param ZKTeco $self An instance of the ZKTecoPhp class.
     *
     * @return array An array of attendance records. Each record contains the following keys:
     *               - id: Badge ID (binary)
     *               - user_id: User ID
     *               - state: Attendance state (e.g., 1 - Check In, 2 - Check Out)
     *               - timestamp: Timestamp of the attendance record
     *               - type: Attendance type (might be device specific)
     */
    public static function get(ZKTeco $self, $callback)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__; // Set the current section for internal tracking (optional)

        $command = Util::CMD_ATT_LOG_RRQ; // Attendance log read request command
        $command_string = ''; // Empty command string (no additional data needed)

        $session = $self->_command($command, $command_string, Util::COMMAND_TYPE_DATA);
        if ($session === false) {
            return []; // Return empty array if session fails
        }

        $attData = Util::recData($self); // Read attendance data from the device

        $attendance = [];
        if (!empty($attData)) {
            $attData = substr($attData, 10); // Skip the first 10 bytes of data

            while (strlen($attData) > 40) { // Loop through each attendance record in the data
                $u = unpack('H78', substr($attData, 0, 39)); // Unpack 78 bytes of data as hexadecimal string

                $u1 = hexdec(substr($u[1], 4, 2)); // Extract first byte of user ID (low order)
                $u2 = hexdec(substr($u[1], 6, 2)); // Extract second byte of user ID (high order)
                $uid = $u1 + ($u2 * 256); // Combine user ID bytes

                $id = hex2bin(substr($u[1], 8, 18)); // Extract badge ID as binary string
                $id = str_replace(chr(0), '', $id); // Remove null bytes from badge ID

                $state = hexdec(substr($u[1], 56, 2)); // Extract attendance state

                $timestamp = Util::decodeTime(hexdec(Util::reverseHex(substr($u[1], 58, 8)))); // Decode timestamp from hex

                $type = hexdec(Util::reverseHex(substr($u[1], 66, 2))); // Extract attendance type

                $data = [ // Add record to the attendance array
                    'uid'         => $uid,
                    'user_id'     => intval($id),
                    'state'       => $state,
                    'record_time' => $timestamp,
                    'type'        => $type,
                    'device_ip'   => $self->_ip,
                ];

                if (is_callable($callback)) {
                    if ($newData = $callback($data)) {
                        $attendance[] = $newData;
                    }
                } else {
                    $attendance[] = $data;
                }

                $attData = substr($attData, 40); // Move to the next attendance record data
            }
        }

        return $attendance; // Return the parsed attendance data
    }

    /**
     * Clears attendance data from the ZKTecoPhp device.
     *
     * This method sends a command to the ZKTecoPhp device to clear all stored attendance records.
     *
     * @param ZKTeco $self An instance of the ZKTecoPhp class.
     *
     * @return bool|mixed True on success, error message on failure.
     */
    public static function clear(ZKTeco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__; // Set the current section for internal tracking (optional)

        $command = Util::CMD_CLEAR_ATT_LOG; // Clear attendance log command
        $command_string = ''; // Empty command string (no additional data needed)

        return $self->_command($command, $command_string); // Send the clear command
    }
}
