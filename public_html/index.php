<?php
/**
 * index.php
 *
 * This file is part of PHPXI.
 *
 * @package    index.php @ 2021-05-11T16:29:58.781Z
 * @author     Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * @copyright  Copyright © 2021 PHPXI Open Source MVC Framework
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt  GNU GPL 3.0
 * @version    1.6.2
 * @link       http://phpxi.net
 *
 * PHPXI is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PHPXI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PHPXI.  If not, see <https://www.gnu.org/licenses/>.
 */

if (PHP_VERSION < "7.2") {
    echo "Your PHP Version : " . PHP_VERSION . "\n<br />";
    echo "Please, Upgrade PHP (7.2) Version";
    exit;
}

const PUBLIC_PATH = __DIR__;

define("PHPXI_PATH", realpath("../PHPXI/"));

const SYSTEM_PATH = PHPXI_PATH . '/SYSTEM/';

const APPLICATION_PATH = PHPXI_PATH . '/APPLICATION/';

const WEIGHT_PATH = PHPXI_PATH . '/WEIGHT/';

require_once SYSTEM_PATH . "Executive.php";
