<?php
/**
 * Config.php
 *
 * This file is part of PHPXI.
 *
 * @package    Config.php @ 2021-05-11T23:20:29.114Z
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

namespace Config;

class Config
{
    /*
     * Charset
     * if MB_String enabled
     */
    const CHARSET = 'UTF-8';

    /**
     * Project Base URL
     * This should belong to the public_html directory.
     */
    const BASE_URL = 'http://localhost/PHPXI/public_html';

    /**
     * Timezone
     * https://www.php.net/manual/en/timezones.php
     */
    const TIMEZONE = 'Europe/Istanbul';

    /**
     * It tries to minimize the html output.
     * If you want minify applied to HTML output; Change it to true.
     */
    const HTML_OUTPUT_MINIFY = false;

    const MULTI_LANGUAGE = false;

    const DEFAULT_LANGUAGE = 'tr';

}
