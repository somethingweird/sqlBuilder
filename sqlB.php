<?php
class sqlB {
    private $where = '';
    private $table = array();
    private $select = '*';

    public function select($string) {
        $this->select = $string;
        return $this;
    }

    public function table($string) {
        $this->table[] = $string;
        return $this;
    }

    // limit
    public function limit($string) {
        $this->limit = $string;
        return $this;
    }
    public function where($p1, $p2, $p3) {
        $this->where = sprintf('%s %s %s', $p1, $p2, $p3);
        return $this;
    }
    public function orWhere($string) {}
    public function andWhere($string) {}
        
    public function __toString() {
        $o = sprintf ('select %s ', $this->select);
        if (count($this->table) > 0) {
            $o .= 'from ';
            $o .= join (',', $this->table);
        }
        if ($this->where != '') {
            $o .= sprintf (' where %s', $this->where);
        }
        if (isset($this->limit)) {
            $o .= sprintf (' limit %d', $this->limit);
        };
        return trim($o);
    }
};

$sql = new sqlB();
$sql->select('name')
    ->table('hello a')
    ->where('a', '=', 'b')
    ->limit(10)
    ->table('registration b');
echo $sql;
