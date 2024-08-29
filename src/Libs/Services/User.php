<?php

namespace CodingLibs\ZktecoPhp\Libs\Services;

use CodingLibs\ZktecoPhp\Exceptions\InvalidParamException;
use CodingLibs\ZktecoPhp\Libs\ZKTeco;

class User
{
    /**
     * @param ZKTeco     $self
     * @param int        $uid      Unique ID (max 65535)
     * @param int|string $userid   (max length = 9, only numbers - depends device setting)
     * @param string     $name     (max length = 24)
     * @param int|string $password (max length = 8, only numbers - depends device setting)
     * @param int        $role     Default Util::LEVEL_USER
     * @param int        $cardno   Default 0 (max length = 10, only numbers)
     *
     * @return bool|mixed
     */
    public static function set(ZKTeco $self, $uid, $userid, $name, $password, $role = Util::LEVEL_USER, $cardno = 0)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $userid = trim($userid);
        $name = substr(trim($name), 0, 24);
        $password = substr(trim($password), 0, 8);
        $cardno = substr(trim($cardno), 0, 10);

        if ($uid === 0 || $uid > Util::USHRT_MAX) {
            throw new InvalidParamException('UID should be between 1 and '.Util::USHRT_MAX);
        }

        if (strlen($userid) > 9) {
            throw new InvalidParamException('UserId length should not be greater than 9 chars');
        }

        $command = Util::CMD_SET_USER;
        $byte1 = chr((int) ($uid % 256));
        $byte2 = chr((int) ($uid >> 8));
        $cardno = hex2bin(Util::reverseHex(dechex($cardno)));

        $command_string = implode('', [
            $byte1,
            $byte2,
            chr($role),
            str_pad($password, 8, chr(0)),
            str_pad($name, 24, chr(0)),
            str_pad($cardno, 4, chr(0)),
            str_pad(chr(1), 9, chr(0)),
            str_pad($userid, 9, chr(0)),
            str_repeat(chr(0), 15),
        ]);

        return $self->_command($command, $command_string);
    }

    /**
     * @param ZKTeco $self
     *
     * @return array [userid, name, cardno, uid, role, password]
     */
    public static function get(ZKTeco $self, callable $callback = null)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_USER_TEMP_RRQ;
        $command_string = chr(Util::FCT_USER);

        $session = $self->_command($command, $command_string, Util::COMMAND_TYPE_DATA);
        if ($session === false) {
            return [];
        }

        $userData = Util::recData($self);

        $users = [];
        if (!empty($userData)) {
            $userData = substr($userData, 11);

            while (strlen($userData) > 72) {
                $u = unpack('H144', substr($userData, 0, 72));

                $u1 = hexdec(substr($u[1], 2, 2));
                $u2 = hexdec(substr($u[1], 4, 2));
                $uid = $u1 + ($u2 * 256);
                $cardno = hexdec(substr($u[1], 78, 2).substr($u[1], 76, 2).substr($u[1], 74, 2).substr($u[1], 72, 2)).' ';
                $role = hexdec(substr($u[1], 6, 2)).' ';
                $password = hex2bin(substr($u[1], 8, 16)).' ';
                $name = hex2bin(substr($u[1], 24, 74)).' ';
                $userid = hex2bin(substr($u[1], 98, 72)).' ';

                //Clean up some messy characters from the user name
                $password = explode(chr(0), $password, 2);
                $password = $password[0];
                $userid = explode(chr(0), $userid, 2);
                $userid = $userid[0];
                $name = explode(chr(0), $name, 3);
                $name = mb_convert_encoding($name[0], 'UTF-8', 'ISO-8859-1');
                $cardno = str_pad($cardno, 11, '0', STR_PAD_LEFT);

                if ($name == '') {
                    $name = $userid;
                }

                $data = [
                    'uid'       => $uid,
                    'user_id'   => intval($userid),
                    'name'      => $name,
                    'role'      => intval($role),
                    'password'  => $password,
                    'card_no'   => $cardno,
                    'device_ip' => $self->_ip,
                ];

                if (is_callable($callback)) {
                    if($newData = $callback($data)){
                        $users[$userid] = $newData;
                    }
                } else {
                    $users[$userid] = $data;
                }

                $userData = substr($userData, 72);
            }
        }

        return $users;
    }

    /**
     * Delete users by callable method conditionally.
     *
     * @param ZKTeco $self
     *
     * @return bool|mixed
     */
    public static function deleteUsers(ZKTeco $self, callable $callback)
    {
        $self->_section = __METHOD__;

        Ping::run($self);

        $users = static::get($self);
        foreach ($users as $user) {
            if ($callback($user)) {
                static::remove($self, $user->uid);
            }
        }
    }

    /**
     * @param ZKTeco $self
     *
     * @return bool|mixed
     */
    public static function clearAll(ZKTeco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_CLEAR_DATA;
        $command_string = '';

        return $self->_command($command, $command_string);
    }

    /**
     * @param ZKTeco $self
     *
     * @return bool|mixed
     */
    public static function clearAdminPriv(ZKTeco $self)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_CLEAR_ADMIN;
        $command_string = '';

        return $self->_command($command, $command_string);
    }

    /**
     * @param ZKTeco $self
     * @param int    $uid
     *
     * @return bool|mixed
     */
    public static function remove(ZKTeco $self, $uid)
    {
        // ping to device
        Ping::run($self);

        $self->_section = __METHOD__;

        $command = Util::CMD_DELETE_USER;
        $byte1 = chr((int) ($uid % 256));
        $byte2 = chr((int) ($uid >> 8));
        $command_string = ($byte1.$byte2);

        return $self->_command($command, $command_string);
    }
}
