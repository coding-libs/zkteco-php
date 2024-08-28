<?php

namespace CodingLibs\ZktecoPhp\Libs\Services;

use CodingLibs\ZktecoPhp\Libs\ZKTeco;
use ErrorException;
use Exception;

class Connect
{
    /**
     * Establishes a connection with the ZKTecoPhp device.
     *
     * @param ZKTeco $self The instance of the ZKTecoPhp class.
     *
     * @return bool Returns true if the connection is successfully established, false otherwise.
     */
    public static function connect(ZKTeco $self)
    {
        // ping to device
        Ping::run($self);

        // Set the current section of the code.
        $self->_section = __METHOD__;

        // Define command and other necessary variables.
        $command = Util::CMD_CONNECT;
        $command_string = '';
        $chksum = 0;
        $session_id = 0;
        $reply_id = -1 + Util::USHRT_MAX;

        // Create the header for the command.
        $buf = Util::createHeader($command, $chksum, $session_id, $reply_id, $command_string);

        // Send the command to the ZKTecoPhp device.
        socket_sendto($self->_zkclient, $buf, strlen($buf), 0, $self->_ip, $self->_port);

        try {
            // Attempt to receive data from the device.
            @socket_recvfrom($self->_zkclient, $self->_data_recv, 1024, 0, $self->_ip, $self->_port);

            // If data is received, process it.
            if (strlen($self->_data_recv) > 0) {
                // Unpack the received data to extract session ID.
                $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6', substr($self->_data_recv, 0, 8));

                // Convert session ID from hex to decimal.
                $session = hexdec($u['h6'].$u['h5']);

                // If session ID is empty, return false.
                if (empty($session)) {
                    return false;
                }

                // Set the session ID in the ZKTecoPhp instance.
                $self->_session_id = $session;

                // Check if the received data is valid.
                return Util::checkValid($self->_data_recv);
            } else {
                // If no data is received, return false.
                return false;
            }
        } catch (ErrorException $e) {
            // Catch any error exceptions and return false.
            return false;
        } catch (Exception $e) {
            // Catch any general exceptions and return false.
            return false;
        }
    }

    /**
     * Disconnects from the ZKTecoPhp device.
     *
     * @param ZKTeco $self The instance of the ZKTecoPhp class.
     *
     * @return bool Returns true if the disconnection is successful, false otherwise.
     */
    public static function disconnect(ZKTeco $self)
    {
        if (!Ping::run($self)) {
            return true;
        }

        // Set the current section of the code.
        $self->_section = __METHOD__;

        // Define command and other necessary variables.
        $command = Util::CMD_EXIT;
        $command_string = '';
        $chksum = 0;
        $session_id = $self->_session_id;

        // Unpack the data received during connection to extract reply ID.
        $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6/H2h7/H2h8', substr($self->_data_recv, 0, 8));
        $reply_id = hexdec($u['h8'].$u['h7']);

        // Create the header for the command.
        $buf = Util::createHeader($command, $chksum, $session_id, $reply_id, $command_string);

        // Send the command to the ZKTecoPhp device.
        socket_sendto($self->_zkclient, $buf, strlen($buf), 0, $self->_ip, $self->_port);

        try {
            // Attempt to receive data from the device.
            @socket_recvfrom($self->_zkclient, $self->_data_recv, 1024, 0, $self->_ip, $self->_port);

            // Reset the session ID in the ZKTecoPhp instance.
            $self->_session_id = 0;

            // Check if the received data is valid.
            return Util::checkValid($self->_data_recv);
        } catch (ErrorException $e) {
            // Catch any error exceptions and return false.
            return false;
        } catch (Exception $e) {
            // Catch any general exceptions and return false.
            return false;
        }
    }
}
