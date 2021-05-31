<?php
/**
 * Hash.php
 *
 * This file is part of PHPXI.
 *
 * @package    Hash.php @ 2021-05-28T06:25:29.607Z
 * @author     Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * @copyright  Copyright © 2021 PHPXI Open Source MVC Framework
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt  GNU GPL 3.0
 * @version    1.6.2.2
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

namespace Config;

class Hash
{
    /**
     * Hash::hash() and Hash::hash_file() vaccination default algorithm for.
     * You can use hash_algos() to see the algorithms you can use.
     */
    const ALGORITHM = 'md5';

    /**
     * Hash::encrypt() and Hash::decrypt() vaccination default method for.
     * You can use openssl_get_cipher_methods() to see the methods you can use.
     */
    const METHOD = 'aes-128-cbc';

    /**
     * Hash::encrypt() and Hash::decrypt() default key.
     */
    const KEY = 'ahmet';

    /**
     * Hash::encrypt() and Hash::decrypt() default iv.
     */
    const IV = '1234567890123456'; //16 bytes length STRING

    /**
     * Hash::encrypt() and Hash::decrypt() default salt.
     */
    const SALT = 'y680+tU+@Gsq;gWw1x?dB?8J`2';

}