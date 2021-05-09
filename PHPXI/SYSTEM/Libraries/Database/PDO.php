<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Database;

use \PHPXI\Libraries\Database\SQL as SQL;
use \PHPXI\Libraries\Debugging\Logger as Logger;

class PDO
{

    private $host;
    private $user;
    private $password;
    private $name;
    private $prefix;
    private $charset;
    private $collation;
    private $driver;

    public $pdo = null;

    protected $error = [];

    protected $get;
    private $num_rows = 0;
    private $insert_id = 0;
    private $query_size = 0;

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
        if ($query == "") {
            return $this->get->fetch();
        } else {
            return $query->fetch();
        }
    }

    public function rows($query = "")
    {
        if ($query == "") {
            return $this->get->fetchAll();
        } else {
            return $query->fetchAll();
        }
    }

    public function count()
    {
        $query = $this->pdo->query(SQL::query());
        $this->query_size++;
        return $query->rowCount();
    }

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
                    "sql" => $sql,
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

    public function exec($sql)
    {
        $this->query_size++;
        return $this->pdo->exec($sql);
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
        $query = $this->query(SQL::update($from, $data));
        if ($query->rowCount()) {
            return true;
        } else {
            return false;
        }
    }

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

    public function num_rows($query = "")
    {
        if ($query != "") {
            return $query->rowCount();
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
        return $this->exec(SQL::drop($table));
    }

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
                                "table" => $table[0],
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
