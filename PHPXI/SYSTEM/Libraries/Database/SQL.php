<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Database;

class SQL
{

    private static $prefix;
    private static $select = [];
    private static $from = [];
    public static $selected_from = '';
    private static $where = [];
    private static $and_where = [];
    private static $or_where = [];
    private static $having = [];
    private static $group_by = [];
    private static $limit;
    private static $order_by = [];

    public static function prefix(string $prefix)
    {
        self::$prefix = $prefix;
    }

    private static function escape_string($value)
    {
        return preg_replace('~[\x00\x0A\x0D\x1A\x22\x27\x5C]~u', '\\\$0', $value);
    }

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

    public static function from($from)
    {
        self::$from[] = $from;
        self::selected_from($from);

    }

    public static function selected_from($from)
    {
        self::$selected_from = $from;
    }

    public static function join($from)
    {
        return self::from($from);
    }

    public static function join_where(array $join_column, array $from_column)
    {
        $join = '`' . self::$prefix . $join_column[0] . '`.`' . $join_column[1] . '`';

        $from = '`' . self::$prefix . $from_column[0] . '`.`' . $from_column[1] . '`';

        self::$where[] = $from . '=' . $join;

    }

    public static function where($column, $value, $operator)
    {
        self::$where[] = self::operator_where($column, $value, $operator);
    }

    public static function and_where($column, $value, $operator)
    {
        self::$and_where[] = self::operator_where($column, $value, $operator);
    }

    public static function or_where($column, $value, $operator)
    {
        self::$or_where[] = self::operator_where($column, $value, $operator);
    }

    public static function having($column, $value, $operator)
    {
        self::$having[] = self::operator_where($column, $value, $operator);
    }

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

    public static function group_by($group)
    {
        self::$group_by[] = $group;
    }

    public static function limit($limit)
    {
        self::$limit = $limit;
    }

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

    public static function drop(string $from)
    {
        $sql = "DROP TABLE `" . self::$prefix . $from . "`";
        return $sql;
    }

    public static function truncate(string $from)
    {
        $sql = "TRUNCATE `" . self::$prefix . $from . "`";
        return $sql;
    }

    public static function analyze(string $from)
    {
        $sql = "ANALYZE TABLE `" . self::$prefix . $from . "`";
        return $sql;
    }

    public static function check(string $from)
    {
        $sql = "CHECK TABLE `" . self::$prefix . $from . "`";
        return $sql;
    }

    public static function repair(string $from)
    {
        $sql = "REPAIR TABLE `" . self::$prefix . $from . "`";
        return $sql;
    }

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
