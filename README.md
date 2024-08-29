<p align="center"><a href="https://www.zkteco.com/" target="_blank"><img src="https://raw.githubusercontent.com/coding-libs/zkteco-js/master/logo.jpg" width="400" alt="Zkteco Logo"></a></p>


## <span style="color:red;">Warning</span>

**⚠️ This repository is not recommended for use in production. ⚠️**

This repository is currently in development and may contain bugs or incomplete features. Use at your own risk and do not deploy to a production environment.

# About zkteco-php
The zkteco-php library provides a robust solution for php/laravel developers to interface with ZK BioMetric Fingerprint Attendance Devices. Its user-friendly API allows seamless extraction of data, such as registered users, logs, and device versions. Developers can also add users, retrieve real-time logs, and clear attendance records. Using a socket connection, the library ensures fast and reliable data exchange. Whether creating an attendance system or a time-and-attendance management application, zkteco-js is the essential tool for integrating biometric devices efficiently.

### Installation

```bash
composer require coding-libs/zkteco-php
```

### Usage Example

```php

// Uncomment the line below if you are not using a PHP framework and need to manually load Composer dependencies.
// require_once "vendor/autoload.php";

use CodingLibs\ZktecoPhp\Libs\ZKTeco;
$zktecoLib = new Zkteco('192.168.1.1');
$zkteco->connect();

$zktecoLib->vendorName(); // "ZKTeco Inc.
$zktecoLib->deviceName(); // "F22/ID
$zktecoLib->serialNumber(); // "BOCK201261276
$zktecoLib->pinWidth(); // "14
$zktecoLib->faceFunctionOn(); // "0
$zktecoLib->platform(); // "ZLM60_TFT
$zktecoLib->fmVersion(); // "10
$zktecoLib->ssr(); // "1
$zktecoLib->version(); // "Ver 6.60 Sep 19 2019
$zktecoLib->workCode(); // "0
$zktecoLib->getFingerprint(1); 
$zktecoLib->getUsers(); // users
$zktecoLib->getAttendances(); // attendances logs
$zktecoLib->getTime(); // device time
$zktecoLib->clearAdminPriv(); // Removes the admin privileges from the current user.
$zktecoLib->clearAllUsers(); // clear all users
$zktecoLib->deleteUsers(function($user){
   // condition goes there
}); // delete users conditionally
```

## Contributing

Please see [CONTRIBUTING](https://github.com/coding-libs/zkteco-php/graphs/contributors) for details.
## Security

If you've found a bug regarding security please mail [codinglibs4u@gmail.com](mailto:codinglibs4u@gmail.com) instead of using the issue tracker.

## Alternatives

- [adrobinoga/zk-protocol](https://github.com/adrobinoga/zk-protocol)
- [dnaextrim/python_zklib](https://github.com/dnaextrim/python_zklib)
- [caobo171/node-zklib](https://github.com/caobo171/node-zklib)


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
