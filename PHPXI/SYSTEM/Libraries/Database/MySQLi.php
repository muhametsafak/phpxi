<?php
/**
 * Mysqli.php
 *
 * This file is part of PHPXI.
 *
 * @package    Mysqli.php @ 2021-05-11T20:45:27.981Z
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

namespace PHPXI\Libraries\Database;

use PHPXI\Libraries\Database\SQL as SQL;
use PHPXI\Libraries\Logger\Logger as Logger;

class MySQLi
{

    /**
     * @var mixed
     */
    private $host;
    /**
     * @var mixed
     */
    private $user;
    /**
     * @var mixed
     */
    private $password;
    /**
     * @var mixed
     */
    private $name;
    /**
     * @var mixed
     */
    private $prefix;
    /**
     * @var mixed
     */
    private $charset;
    /**
     * @var mixed
     */
    private $collation;

    /**
     * @var mixed
     */
    public $mysqli;

    /**
     * @var array
     */
    protected $error = [];

    /**
     * @var mixed
     */
    protected $get;
    /**
     * @var int
     */
    private $num_rows = 0;
    /**
     * @var int
     */
    private $insert_id = 0;
    /**
     * @var int
     */
    private $query_size = 0;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->host = $config['host'];
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->name = $config['name'];
        $this->prefix = $config['prefix'];
        $this->charset = $config['charset'];
        $this->collation = $config['collation'];
        SQL::prefix($this->prefix);
        $this->connect();
    }

    public function __destroy()
    {
        $this->disconnect();
    }

    /**
     * @return mixed
     */
    public function connect()
    {
        $this->mysqli = new \mysqli($this->host, $this->user, $this->password, $this->name);
        if ($this->mysqli->connect_errno) {
            Logger::system(
                "MySQLI DB Connect ERROR : {connect_errno} : {connect_error}",
                [
                    "connect_errno" => $this->mysqli->connect_errno,
                    "connect_error" => $this->mysqli->connect_error
                ]
            );
        }
        $this->mysqli->set_charset($this->charset);
        $this->mysqli->query("SET NAMES '" . $this->charset . "' COLLATION '" . $this->collation . "'");
        $this->mysqli->query("SET CHARACTER SET '" . $this->charset . "'");

        return $this;
    }

    public function disconnect()
    {
        $this->mysqli->close();
    }

    /**
     * @param $select
     * @return mixed
     */
    public function select($select = "*")
    {
        SQL::select($select);

        return $this;
    }

    /**
     * @param $from
     * @return mixed
     */
    public function from($from)
    {
        SQL::from($from);

        return $this;
    }

    /**
     * @param $from
     * @return mixed
     */
    public function join($from)
    {
        SQL::join($from);

        return $this;
    }

    /**
     * @param $join_table
     * @param $join_column
     * @param $from_table
     * @param $from_column
     * @return mixed
     */
    public function join_where($join_table, $join_column, $from_table, $from_column)
    {
        SQL::join_where(
            [$join_table => $join_column],
            [$from_table => $from_column]
        );

        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @param $operator
     * @return mixed
     */
    public function where($column, $value, $operator = '=')
    {
        SQL::where($column, $value, $operator);

        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @param $operator
     * @return mixed
     */
    public function and_where($column, $value, $operator = '=')
    {
        SQL::and_where($column, $value, $operator);

        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @param $operator
     * @return mixed
     */
    public function or_where($column, $value, $operator = '=')
    {
        SQL::or_where($column, $value, $operator);

        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @param $operator
     * @return mixed
     */
    public function having($column, $value, $operator = '=')
    {
        SQL::having($column, $value, $operator);

        return $this;
    }

    /**
     * @param $group
     * @return mixed
     */
    public function group_by($group = "")
    {
        SQL::group_by($group);

        return $this;
    }

    /**
     * @param $by
     * @param $order
     * @param $from
     * @return mixed
     */
    public function order_by($by = "", $order = "", $from = "")
    {
        SQL::order_by($by, $order, $from);

        return $this;
    }

    /**
     * @param $limit
     * @return mixed
     */
    public function limit($limit)
    {
        SQL::limit($limit);

        return $this;
    }

    /**
     * @param $from
     * @return mixed
     */
    public function get($from = "")
    {
        if ($from != "") {
            SQL::from($from);
        }

        return $this->get = $this->query(SQL::query());
    }

    /**
     * @param $query
     * @return mixed
     */
    public function row($query = "")
    {
        if ($query != "") {
            return $query->fetch_object();
        } else {
            return $this->get->fetch_object();
        }
    }

    /**
     * @param $query
     */
    public function rows($query = "")
    {
        $data = [];
        if ($query != "") {
            while ($row = $query->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            while ($row = $this->get->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return arrayObject($data);
    }

    /**
     * @return mixed
     */
    public function count()
    {
        $query = $this->mysqli->query(SQL::query());
        $this->query_size++;

        return $query->num_rows;
    }

    public function build()
    {
        return SQL::query();
    }

    /**
     * @param $sql
     * @return mixed
     */
    public function query($sql)
    {
        $query = $this->mysqli->query($sql) or Logger::system(
            "SQL QUERY ERROR : {error} - QUERY : {sql}",
            [
                "error" => $this->mysqli->error,
                "sql" => $sql
            ]
        );
        if (isset($this->mysqli->insert_id)) {
            $this->insert_id = $this->mysqli->insert_id;
        } else {
            $this->insert_id = 0;
        }
        if (isset($query->num_rows)) {
            $this->num_rows = $query->num_rows;
        } else {
            $this->num_rows = 0;
        }
        SQL::clear();
        $this->query_size++;

        return $query;
    }

    /**
     * @param array $data
     * @param string $from
     */
    public function insert(array $data, string $from = "")
    {
        if ($from == "") {
            $from = SQL::$seleted_from;
        }
        if (sizeof($data) > 0) {
            if ($this->query(SQL::insert($from, $data))) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $data
     * @param string $from
     */
    public function update(array $data, string $from = "")
    {
        if ($from == "") {
            $from = SQL::$selected_from;
        }
        if ($this->query(SQL::update($from, $data))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param array $where
     * @param string $from
     */
    public function delete(array $where = [], string $from = "")
    {
        if ($from == "") {
            $from = SQL::$seleted_from;
        }
        if ($this->query(SQL::delete($from, $where))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $query
     * @return mixed
     */
    public function num_rows($query = "")
    {
        if ($query != "") {
            return $query->num_rows;
        } else {
            return $this->num_rows;
        }
    }

    /**
     * @return mixed
     */
    public function insert_id()
    {
        return $this->insert_id;
    }

    /**
     * @return mixed
     */
    public function query_size()
    {
        return $this->query_size;
    }

    /**
     * @param string $table
     * @return mixed
     */
    public function drop(string $table)
    {
        $this->query_size++;

        return $this->mysqli->query(SQL::drop($table));
    }

    /**
     * @param string $table
     * @return mixed
     */
    public function truncate(string $table)
    {
        $this->query_size++;

        return $this->mysqli->query(SQL::truncate($table));
    }

    public function maintenance()
    {
        $query = $this->mysqli->query("SHOW TABLES");
        $this->query_size++;
        if ($query) {
            while ($table = $query->fetch_row()) {
                if ($this->prefix != "") {
                    $len = strlen($this->prefix);
                    $from = substr($table[0], $len, strlen($table[0]));
                    if ($table[0] != $from) {
                        $this->mysqli->query(SQL::check($from));
                        $this->mysqli->query(SQL::analyze($from));
                        $this->mysqli->query(SQL::repair($from));
                        $this->mysqli->query(SQL::optimize($from));
                        $this->query_size += 4;
                        Logger::system(
                            "The table {database}.{table} has been maintained.",
                            [
                                "database" => $this->name,
                                "table" => $table[0]
                            ]
                        );
                    }
                } else {
                    $from = $table[0];
                    $this->mysqli->query(SQL::check($from));
                    $this->mysqli->query(SQL::analyze($from));
                    $this->mysqli->query(SQL::repair($from));
                    $this->mysqli->query(SQL::optimize($from));
                    $this->query_size += 4;
                    Logger::system(
                        "The table {database}.{table} has been maintained.",
                        [
                            "database" => $this->name,
                            "table" => $from
                        ]
                    );
                }
            }

            return true;
        }

        return false;
    }

}
