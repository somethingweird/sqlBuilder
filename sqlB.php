<?php

// alot more function & ideas to steal from github
// should complement the frontend - https://querybuilder.js.org/demo.html

class sqlB {
    private $w = array();
    private $table = array();
    private $select = '*';
    private $group = null;

    public function select($string) {
        $this->select = $string;
        return $this;
    }

    public function table($string) {
        $this->table[] = $string;
        return $this;
    }

    # https://github.com/izniburak/PDOx - thanks.
    public function andGrouped(Closure $obj)
    {
        $this->w[] = ' and (';
        call_user_func_array($obj, [$this]);
        $this->w[] = ')';
        return $this;
    }

    public function orGrouped(Closure $obj)
    {
        $this->grouped = true;
        $this->w[] = ' or (';
        call_user_func_array($obj, [$this]);
        $this->w[] = ')';
        return $this;
    }

    // limit
    public function limit($string) {
        $this->limit = $string;
        return $this;
    }

    public function where() {
        switch(func_num_args()) {
            case 1:  
                $this->w[] = func_get_arg(0);
                break;
            case 2:   
                $this->w[] = vsprintf ('%s = %s',func_get_args());
                break;
            case 3:   
                $this->w[] = vsprintf('%s %s %s',func_get_args());
                break;
        }        
        return $this;
    }

    public function andWhere() {
        switch(func_num_args()) {
            case 1:  
                $this->w[] = sprintf (' and %s', func_get_arg(0));
                break;
            case 2:   
                $this->w[] = vsprintf (' and %s = %s',func_get_args());
                break;
            case 3:   
                $this->w[] = vsprintf(' and %s %s %s',func_get_args());
                break;
        }
        return $this;
    }

    public function orWhere() {
        switch(func_num_args()) {
            case 1:  
                $this->w[] = sprintf (' or %s', func_get_arg(0));
                break;
            case 2:   
                $this->w[] = vsprintf (' or %s = %s',func_get_args());
                break;
            case 3:   
                $this->w[] = vsprintf(' or %s %s %s',func_get_args());
                break;
        }
        return $this;
    }

    public function groupBy($group) {
        if (is_array($group)) {
            $this->group = implode(',', $group);
        } else {
            $this->group = $group;
        };
    }
    
    public function clear() {
        $this->$w = array();
        $this->table = array();
        $this->$select = '*';
        $this->group = null;
    }

    public function __toString() {
        $o = sprintf ('select %s ', $this->select);
        if (count($this->table) > 0) {
            $o .= 'from ';
            $o .= join (',', $this->table);
        }
        if (count($this->w) > 0) {
            $o .= ' where ';
            foreach ($this->w as $v) {
                $o .= sprintf ('%s', $v);
            }
        }
        if (!is_null($this->group)) {
            $o .= sprintf (' group by %s', $this->group);
        }
        if (isset($this->limit)) {
            $o .= sprintf (' limit %d', $this->limit);
        };
        return trim($o);
    }

};


// sample
$sql = new sqlB();

// base query, select a,b,c from NYIT_STUDENT_CONTRACT a where isDeleted != 0
$sql->select('a,b,c')
    ->table('NYIT_STUDENT_CONTRACT a')
    ->where('isDeleted', '!=', 0);

$limit = 10;

// searching...
$type = 'name';
switch ($type) {
    case 'email':
        $sql->andWhere('a.email', 'like', '?');
        break;
    case 'id':
        $sql->andWhere('a.studentid', 'like', '?');
        break;
    case 'name': 
        $sql->select('c,d,e');
        $sql->table('anothertable b');
        $sql->andGrouped(function ($i) {
            $i->where('b.fname', 'like', '?')->orWhere('b.lname', 'like', '?')
            ->orGrouped(function ($x) {
                $x->where('c','d');
            });
        });
        $sql->andWhere('a.award_type', '?')
            ->groupby(array('a','b','c'));
        break;
    case 'supervisor':  
        // supervisor
        break;
    case 'award_type':  
        $sql->andWhere('a.award_type', 'CWS');
        break;
    case 'award_year':  
        $sql->andWhere('a.award_year', 2018);
        break;
}
if (isset($limit)) {
    $sql->limit($limit);
};

echo $sql,"\n";
