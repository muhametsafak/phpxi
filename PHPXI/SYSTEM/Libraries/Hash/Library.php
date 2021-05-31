<?php
/**
 * Hash/Library.php
 *
 * This file is part of PHPXI.
 *
 * @package    Hash/Library.php @ 2021-05-28T06:20:50.370Z
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

namespace PHPXI\Libraries\Hash;

class Library
{

    private $method;
    private $key;
    private $iv;
    private $salt;

    private $algorithm;

    function __construct()
    {
        $this->method = \Config\Hash::METHOD;
        $this->key = \Config\Hash::KEY;
        $this->iv = \Config\Hash::IV;
        $this->salt = \Config\Hash::SALT;

        $this->algorithm = \Config\Hash::ALGORITHM;
    }

    public function method(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    public function salt(string $salt): self
    {
        $this->salt = $salt;
        return $this;
    }

    public function key(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    public function iv(string $iv): self
    {
        $this->iv = $iv;
        return $this;
    }

    public function algorithm(string $algorithm): self
    {
        $this->algorithm = $algorithm;
        return $this;
    }

    private function salt_encode(string $data): string
    {
        return $this->salt . $data;
    }

    private function salt_decode(string $data): string
    {
        return substr($data, strlen($this->salt), strlen($data));
    }

    public function encrypt(string $data)
    {
        return openssl_encrypt($this->salt_encode($data), $this->method, $this->key, 0, $this->iv);
    }

    public function decrypt(string $data)
    {
        $decrypt = openssl_decrypt($data, $this->method, $this->key, 0, $this->iv);
        if(!$decrypt){
            return false;
        }
        return $this->salt_decode($decrypt);
    }

    public function password_hash(string $password)
    {
        return password_hash($this->salt_encode($password), PASSWORD_DEFAULT);
    }

    public function password_verify(string $password, string $hash)
    {
        return password_verify($this->salt_encode($password), $hash);
    }

    public function hash($data)
    {
        return hash($this->algorithm, $data);
    }

    public function hash_file(string $path)
    {
        if(!file_exists($path)){
            return hash_file($this->algorithm, $path);
        }else{
            return false;
        }
    }

}
