<?php
/**
 * Validation/LibraryToken.php
 *
 * This file is part of PHPXI.
 *
 * @package    Validation/LibraryToken.php @ 2021-05-11T22:19:51.993Z
 * @author     Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * @copyright  Copyright © 2021 PHPXI Open Source MVC Framework
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt  GNU GPL 3.0
 * @version    1.6
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

namespace PHPXI\Libraries\Validation;

use PHPXI\Libraries\Input\Base as Base;

class LibraryToken
{

    public function get(): string
    {
        return Base::get("_token", "validation");
    }

    public function verify(): bool
    {
        if (Base::get("_token", "validation")) {
            if (Input::post("_token") == Base::get("_token", "validation")) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

}
