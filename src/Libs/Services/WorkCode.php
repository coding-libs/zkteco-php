<?php

namespace CodingLibs\ZktecoPhp\Libs\Services;

use CodingLibs\ZktecoPhp\Libs\ZktecoLib;

class WorkCode
{
  /**
   * Retrieves work codes configured on the ZKTecoLib device.
   *
   * This method sends a command to the ZKTecoLib device requesting the list of configured work codes.
   * The response may contain information about each work code, depending on the device model.
   *
   * @param ZKTecoLib $self An instance of the ZKTecoLib class.
   * @return bool|mixed The work code data retrieved from the device on success, false on failure.
   *                   The exact format of the data depends on the device model.
   */
  static public function get(ZKTecoLib $self)
  {
    $self->_section = __METHOD__; // Set the current section for internal tracking (optional)

    $command = Util::CMD_DEVICE; // Device information command code
    $command_string = 'WorkCode'; // Specific data request: Work Code information

    return $self->_command($command, $command_string); // Use internal ZKTecoLib method to send the command
  }
}