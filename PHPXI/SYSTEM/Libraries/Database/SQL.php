<?php
/**
 * SQL.php
 *
 * This file is part of PHPXI.
 *
 * @package    SQL.php @ 2021-05-11T20:46:47.213Z
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

class SQL
{

    /**
     * @var mixed
     */
    private static $prefix;
    /**
     * @var array
     */
    private static $select = [];
    /**
     * @var array
     */
    private static $from = [];
    /**
     * @var string
     */
    public static $selected_from = '';
    /**
     * @var array
     */
    private static $where = [];
    /**
     * @var array
     */
    private static $and_where = [];
    /**
     * @var array
     */
    private static $or_where = [];
    /**
     * @var array
     */
    private static $having = [];
    /**
     * @var array
     */
    private static $group_by = [];
    /**
     * @var mixed
     */
    private static $limit;
    /**
     * @var array
     */
    private static $order_by = [];

    /**
     * @param string $prefix
     */
    public static function prefix(string $prefix)
    {
        self::$prefix = $prefix;
    }

    /**
     * @param $value
     */
    private static function escape_string($value)
    {
        return preg_replace('~[\x00\x0A\x0D\x1A\x22\x27\x5C]~u', '\\\$0', $value);
    }

    /**
     * @param $select
     */
    public static function select($select)
    {
        if ($select != '*') {
            $selects = explode(',', $select);
            foreach ($selects as $row) {
                $as_rows = explode(' as ', strtolower($row));
                if (sizeof($as_rows) > 1) {
                    $as = $as_rows[1];
                }
                $rows = explode('.', $as_rows[0]);
                if (sizeof($rows) > 1) {
                    if (trim($rows[1]) == '*') {
                        self::$select[] = '`' . trim($rows[0]) . '`.*';
                    } else {
                        if (isset($as) and $as != '') {
                            self::$select[] = '`' . trim($rows[0]) . '`.`' . trim($rows[1]) . '` AS `' . $as . '`';
                        } else {
                            self::$select[] = '`' . trim($rows[0]) . '`.`' . trim($rows[1]) . '`';
                        }
                    }
                } else {
                    if (isset($as) && $as != '') {
                        self::$select[] = '`' . trim($rows[0]) . '` AS `' . $as . '`';
                    } else {
                        self::$select[] = '`' . trim($rows[0]) . '`';
                    }
                }
                $as = null;
            }
        } else {
            self::$select = '*';
        }

    }

    /**
     * @param $from
     */
    public static function from($from)
    {
        self::$from[] = $from;
        self::selected_from($from);

    }

    /**
     * @param $from
     */
    public static function selected_from($from)
    {
        self::$selected_from = $from;
    }

    /**
     * @param $from
     */
    public static function join($from)
    {
        return self::from($from);
    }

    /**
     * @param array $join_column
     * @param array $from_column
     */
    public static function join_where(array $join_column, array $from_column)
    {
        $join = '`' . self::$prefix . $join_column[0] . '`.`' . $join_column[1] . '`';

        $from = '`' . self::$prefix . $from_column[0] . '`.`' . $from_column[1] . '`';

        self::$where[] = $from . '=' . $join;

    }

    /**
     * @param $column
     * @param $value
     * @param $operator
     */
    public static function where($column, $value, $operator)
    {
        self::$where[] = self::operator_where($column, $value, $operator);
    }

    /**
     * @param $column
     * @param $value
     * @param $operator
     */
    public static function and_where($column, $value, $operator)
    {
        self::$and_where[] = self::operator_where($column, $value, $operator);
    }

    /**
     * @param $column
     * @param $value
     * @param $operator
     */
    public static function or_where($column, $value, $operator)
    {
        self::$or_where[] = self::operator_where($column, $value, $operator);
    }

    /**
     * @param $column
     * @param $value
     * @param $operator
     */
    public static function having($column, $value, $operator)
    {
        self::$having[] = self::operator_where($column, $value, $operator);
    }

    /**
     * @param $column
     * @param $value
     * @param $operator
     * @return mixed
     */
    private static function operator_where($column, $value, $operator)
    {
        $table = self::$prefix . self::$selected_from;

        if (is_string($value)) {
            $value = self::escape_string($value);
        }
        if (is_array($value)) {
            $values = [];
            foreach ($value as $val) {
                $values[] = self::escape_string($val);
            }
            $value = $values;
        }
        $return = "";
        switch (strtolower($operator)) {
            case '=':
                $return = "`" . $table . "`.`" . $column . "` = '" . $value . "'";
                break;
            case 'in':
                if (is_array($value)) {
                    $value = implode(", ", $value);
                }
                $return = "`" . $table . "`.`" . $column . "` IN (" . $value . ")";
                break;
            case 'not in':
                if (is_array($value)) {
                    $value = implode(", ", $value);
                }
                $return = "`" . $table . "`.`" . $column . "` NOT IN (" . $value . ")";
                break;
            case 'like':
                $return = "`" . $table . "`.`" . $column . "` LIKE '" . $value . "'";
                break;
            case 'not like':
                $return = "`" . $table . "`.`" . $column . "` NOT LIKE '" . $value . "'";
                break;
            case 'between':
                $return = "`" . $table . "`.`" . $column . "` BETWEEN '" . $value[0] . " AND " . $value[1] . "'";
                break;
            case 'not between':
                $return = "`" . $table . "`.`" . $column . "` NOT BETWEEN '" . $value[0] . " AND " . $value[1] . "'";
                break;
            default:
                $return = "`" . $table . "`.`" . $column . "` " . $operator . " '" . $value . "'";
                break;
        }

        return $return;
    }

    /**
     * @param $group
     */
    public static function group_by($group)
    {
        self::$group_by[] = $group;
    }

    /**
     * @param $limit
     */
    public static function limit($limit)
    {
        self::$limit = $limit;
    }

    /**
     * @param $by
     * @param $oder
     * @param $from
     */
    public static function order_by($by = "", $oder = "", $from = "")
    {
        if ($from == "") {
            $from = self::$selected_from;
        }
        if ($order == "") {
            self::$order_by[] = $by;
        } else {
            if (strtolower($order) == "asc") {
                $order = "ASC";
            } else {
                $order = "DESC";
            }
            self::$order_by[] = "`" . self::$prefix . $from . "`.`" . $by . "` " . $order;
        }

    }

    /**
     * @return mixed
     */
    private static function query_where_create()
    {
        $wheres = [];
        if (is_array(self::$where) && sizeof(self::$where) > 0) {
            $wheres[] = implode(" AND ", self::$where);
        }
        if (is_array(self::$and_where) && sizeof(self::$and_where) > 0) {
            $wheres[] = implode(" AND ", self::$and_where);
        }
        if (is_array(self::$or_where) && sizeof(self::$or_where) > 0) {
            $wheres[] = implode(" OR ", self::$or_where);
        }
        if (sizeof($wheres) > 0) {
            $where = implode(" AND ", $wheres);
        } else {
            $where = '1';
        }

        return $where;
    }

    /**
     * @return mixed
     */
    public static function query()
    {
        $sqls = [];

        if (is_array(self::$select) && sizeof(self::$select) > 0) {
            $sqls['SELECT'] = implode(", ", self::$select);
        } else {
            $sqls['SELECT'] = self::$select;
        }

        $froms = [];
        foreach (self::$from as $row) {
            $froms[] = "`" . self::$prefix . $row . "`";
        }
        $sqls['FROM'] = implode(", ", $froms);

        $sqls['WHERE'] = self::query_where_create();

        if (sizeof(self::$group_by) > 0) {
            $sqls['GROUP BY'] = implode(", ", self::$group_by);
        }

        if (sizeof(self::$having) > 0) {
            $sqls['HAVING'] = implode(" AND ", self::$having);
        }

        if (is_array(self::$order_by) && sizeof(self::$order_by) > 0) {
            $sqls['ORDER BY'] = implode(", ", self::$order_by);
        }

        if (trim(self::$limit) != "") {
            $sqls['LIMIT'] = self::$limit;
        }

        $sql = null;
        foreach ($sqls as $key => $value) {
            $sql .= " " . $key . " " . $value;
        }

        return $sql;
    }

    /**
     * @param string $from
     * @param array $data
     * @return mixed
     */
    public static function insert(string $from, array $data)
    {
        $table = self::$prefix . $from;
        $keys = [];
        $values = [];
        foreach ($data as $key => $value) {
            $keys[] = "`" . $key . "`";
            $values[] = "'" . self::escape_string($value) . "'";
        }
        $sql = "INSERT INTO `" . $table . "` (" . implode(", ", $keys) . ") VALUES (" . implode(", ", $value) . ");";

        return $sql;
    }

    /**
     * @param string $from
     * @param array $where
     * @return mixed
     */
    public static function delete(string $from, array $where = [])
    {
        $table = self::$prefix . $from;
        if (sizeof($where) > 0) {
            $rows = [];
            foreach ($where as $key => $value) {
                $rows[] = "`" . $key . "` = '" . self::escape_string($value) . "'";
            }
            $sql = "DELETE FROM `" . $table . "` WHERE " . implode(" AND ", $rows);
        } else {
            $sql = "DELETE FROM `" . $table . "` WHERE " . self::query_where_create();
        }

        return $sql;
    }

    /**
     * @param string $from
     * @param array $data
     * @return mixed
     */
    public static function update(string $from, array $data)
    {
        $table = self::$prefix . $from;
        $rows = [];
        foreach ($data as $key => $value) {
            $rows[] = "`" . $key . "` = '" . self::escape_string($value) . "'";
        }

        $sql = "UPDATE `" . $table . "` SET " . implode(", ", $rows) . " WHERE " . self::query_where_create();

        return $sql;
    }

    /**
     * @param string $from
     * @return mixed
     */
    public static function drop(string $from)
    {
        $sql = "DROP TABLE `" . self::$prefix . $from . "`";

        return $sql;
    }

    /**
     * @param string $from
     * @return mixed
     */
    public static function truncate(string $from)
    {
        $sql = "TRUNCATE `" . self::$prefix . $from . "`";

        return $sql;
    }

    /**
     * @param string $from
     * @return mixed
     */
    public static function analyze(string $from)
    {
        $sql = "ANALYZE TABLE `" . self::$prefix . $from . "`";

        return $sql;
    }

    /**
     * @param string $from
     * @return mixed
     */
    public static function check(string $from)
    {
        $sql = "CHECK TABLE `" . self::$prefix . $from . "`";

        return $sql;
    }

    /**
     * @param string $from
     * @return mixed
     */
    public static function repair(string $from)
    {
        $sql = "REPAIR TABLE `" . self::$prefix . $from . "`";

        return $sql;
    }

    /**
     * @param string $from
     * @return mixed
     */
    public static function optimize(string $from)
    {
        $sql = "OPTIMIZE TABLE `" . self::$prefix . $from . "`";

        return $sql;
    }

    public static function clear()
    {
        self::$select = [];
        self::$from = [];
        self::$selected_from = '';
        self::$where = [];
        self::$and_where = [];
        self::$or_where = [];
        self::$having = [];
        self::$group_by = [];
        self::$limit = null;
        self::$order_by = [];
    }

}
