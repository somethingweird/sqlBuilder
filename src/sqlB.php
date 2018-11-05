<?php

namespace NYIT;
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
    public function andGrouped(\Closure $obj)
    {
        $this->w[] = ' and (';
        call_user_func_array($obj, [$this]);
        $this->w[] = ')';
        return $this;
    }

    public function orGrouped(\Closure $obj)
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


