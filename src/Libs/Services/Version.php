<?php

namespace CodingLibs\ZktecoPhp\Libs\Services;

use CodingLibs\ZktecoPhp\Libs\Services\Util;
use CodingLibs\ZktecoPhp\Libs\ZKTeco;

class Version
{
  /**
   * Retrieves the ZKTecoLib device version information.
   *
   * This method sends a version command to the ZKTecoLib device and retrieves the response containing
   * the device's firmware version.
   *
   * @param ZKTecoLib $self An instance of the ZKTecoLib class.
   * @return bool|mixed The device version string on success, false on failure.
   */
  static public function get(ZKTecoLib $self)
  {
    $self->_section = __METHOD__; // Set the current section for internal tracking (optional)

    $command = Util::CMD_VERSION; // Version information command code
    $command_string = ''; // Empty command string (no additional data needed)

    return $self->_command($command, $command_string); // Use internal ZKTecoLib method to send the command
  }
}