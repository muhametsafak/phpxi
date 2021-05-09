<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Database;

use \PHPXI\Libraries\Database\SQL as SQL;
use \PHPXI\Libraries\Debugging\Logger as Logger;

class MySQLi
{

    private $host;
    private $user;
    private $password;
    private $name;
    private $prefix;
    private $charset;
    private $collation;

    public $mysqli;

    protected $error = [];

    protected $get;
    private $num_rows = 0;
    private $insert_id = 0;
    private $query_size = 0;

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

    public function connect()
    {
        $this->mysqli = new \mysqli($this->host, $this->user, $this->password, $this->name);
        if ($this->mysqli->connect_errno) {
            Logger::system(
                "MySQLI DB Connect ERROR : {connect_errno} : {connect_error}",
                [
                    "connect_errno" => $this->mysqli->connect_errno,
                    "connect_error" => $this->mysqli->connect_error,
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

    public function select($select = "*")
    {
        SQL::select($select);
        return $this;
    }

    public function from($from)
    {
        SQL::from($from);
        return $this;
    }

    public function join($from)
    {
        SQL::join($from);
        return $this;
    }

    public function join_where($join_table, $join_column, $from_table, $from_column)
    {
        SQL::join_where(
            [$join_table => $join_column],
            [$from_table => $from_column]
        );
        return $this;
    }

    public function where($column, $value, $operator = '=')
    {
        SQL::where($column, $value, $operator);
        return $this;
    }

    public function and_where($column, $value, $operator = '=')
    {
        SQL::and_where($column, $value, $operator);
        return $this;
    }

    public function or_where($column, $value, $operator = '=')
    {
        SQL::or_where($column, $value, $operator);
        return $this;
    }

    public function having($column, $value, $operator = '=')
    {
        SQL::having($column, $value, $operator);
        return $this;
    }

    public function group_by($group = "")
    {
        SQL::group_by($group);
        return $this;
    }

    public function order_by($by = "", $order = "", $from = "")
    {
        SQL::order_by($by, $order, $from);
        return $this;
    }

    public function limit($limit)
    {
        SQL::limit($limit);
        return $this;
    }

    public function get($from = "")
    {
        if ($from != "") {
            SQL::from($from);
        }
        return $this->get = $this->query(SQL::query());
    }

    public function row($query = "")
    {
        if ($query != "") {
            return $query->fetch_object();
        } else {
            return $this->get->fetch_object();
        }
    }

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

    public function query($sql)
    {
        $query = $this->mysqli->query($sql) or Logger::system(
            "SQL QUERY ERROR : {error} - QUERY : {sql}",
            [
                "error" => $this->mysqli->error,
                "sql" => $sql,
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

    public function num_rows($query = "")
    {
        if ($query != "") {
            return $query->num_rows;
        } else {
            return $this->num_rows;
        }
    }

    public function insert_id()
    {
        return $this->insert_id;
    }

    public function query_size()
    {
        return $this->query_size;
    }

    public function drop(string $table)
    {
        $this->query_size++;
        return $this->mysqli->query(SQL::drop($table));
    }

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
                                "table" => $table[0],
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
                            "table" => $from,
                        ]
                    );
                }
            }
            return true;
        }
        return false;
    }

}
