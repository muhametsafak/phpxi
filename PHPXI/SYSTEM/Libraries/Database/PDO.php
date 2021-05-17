<?php
/**
 * PDO.php
 *
 * This file is part of PHPXI.
 *
 * @package    PDO.php @ 2021-05-11T20:46:17.848Z
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
use PHPXI\Libraries\Debugging\Logger as Logger;

class PDO
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
    private $driver;

    /**
     * @var mixed
     */
    public $pdo = null;

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
    public function __contruct(array $config)
    {
        $this->host = $config['host'];
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->name = $config['name'];
        $this->prefix = $config['prefix'];
        $this->charset = $config['charset'];
        $this->collation = $config['collation'];
        $this->driver = $config['driver'];
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
    private function connect()
    {
        try {
            $con_link = $this->driver . ":host=" . $this->host . ";dbname=" . $this->name . ";charset=" . $this->charset;
            $this->pdo = new \PDO($con_link, $this->user, $this->password);
            $this->pdo->exec("SET NAMES '" . $this->charset . "' COLLATION '" . $this->collation . "'");
            $this->pdo->exec("SET CHARACTER SET '" . $this->charset . "'");
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            Logger::system(
                "PDO DB Connect ERROR : {error}",
                ["error" => $e->getMessage()]
            );
        }

        return $this;
    }

    private function disconnect()
    {
        $this->pdo = null;
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
        if ($query == "") {
            return $this->get->fetch();
        } else {
            return $query->fetch();
        }
    }

    /**
     * @param $query
     * @return mixed
     */
    public function rows($query = "")
    {
        if ($query == "") {
            return $this->get->fetchAll();
        } else {
            return $query->fetchAll();
        }
    }

    /**
     * @return mixed
     */
    public function count()
    {
        $query = $this->pdo->query(SQL::query());
        $this->query_size++;

        return $query->rowCount();
    }

    /**
     * @param $query
     * @return mixed
     */
    public function column($query = "")
    {
        if ($query == "") {
            return $this->get->fetchColumn();
        } else {
            return $query->fetchColumn();
        }
    }

    public function build()
    {
        return SQL::query();
    }

    /**
     * @param $sql
     * @param $params
     * @return mixed
     */
    public function query($sql, $params = null)
    {
        try {
            if (is_null($params)) {
                $query = $this->pdo->query($sql);
            } else {
                $query = $this->pdo->prepare($sql);
                $query->execute($params);
            }
        } catch (\PDOException $e) {
            Logger::system(
                "SQL QUERY ERROR : {error} - QUERY : {sql}",
                [
                    "error" => $e->getMessage(),
                    "sql" => $sql
                ]
            );
        }
        if ($this->pdo->lastInsertId()) {
            $this->insert_id = $this->pdo->lastInsertId();
        } else {
            $this->insert_id = 0;
        }
        $this->num_rows = $query->rowCount();
        SQL::clear();
        $this->query_size++;

        return $query;
    }

    /**
     * @param $sql
     * @return mixed
     */
    public function exec($sql)
    {
        $this->query_size++;

        return $this->pdo->exec($sql);
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
        $query = $this->query(SQL::update($from, $data));
        if ($query->rowCount()) {
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
            $from = SQL::$selected_from;
        }
        $query = $this->query(SQL::delete($from, $data));
        if ($query->rowCount()) {
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
            return $query->rowCount();
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
        return $this->exec(SQL::drop($table));
    }

    /**
     * @param string $table
     * @return mixed
     */
    public function truncate(string $table)
    {
        return $this->exec(SQL::truncate($table));
    }

    public function maintenance()
    {
        $query = $this->pdo->query("SHOW TABLES");
        $this->query_size++;
        $query->setFetchMode(\PDO::FETCH_NUM);
        if ($query) {
            foreach ($query as $table) {
                if ($this->prefix != "") {
                    $len = strlen($this->prefix);
                    $from = substr($table[0], $len, strlen($table[0]));
                    if ($table[0] != $from) {
                        $this->exec(SQL::check($from));
                        $this->exec(SQL::analyze($from));
                        $this->exec(SQL::repair($from));
                        $this->exec(SQL::optimize($from));
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
                    $this->exec(SQL::check($from));
                    $this->exec(SQL::analyze($from));
                    $this->exec(SQL::repair($from));
                    $this->exec(SQL::optimize($from));
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
