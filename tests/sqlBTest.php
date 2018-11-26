<?php
use PHPUnit\Framework\TestCase;
require __DIR__."/../vendor/autoload.php";
final class sqlBTest extends TestCase {
    // select * from table
    // select col1 from table
    // select col1, col2 from table
    // select col1, col2 from table where col1 = col2
    // select * from table where col1 = col2 and col2=col3
    // select * from table where col1 = col2 or col2=col3
    // select * fromt able where (col1 = col2 and col2=col3) or col4=col5
    // select * fromt able where (col1 = col2 and col2=col3) and col4=col5

    private $obj;

    protected function setUp()
    {
        $this->obj = new NYIT\sqlB();
    }

    public function test1() {
        // select * from table
        $sql = $this->obj;
        $sql->table('table');
        $this->assertEquals('select * from table', $sql);
    }

    public function test2() {
        // select col1 from table
        $sql = $this->obj;
        $sql->select('col1');
        $sql->table('table1');
        $this->assertEquals('select col1 from table1',$sql);
    }

    public function test3() {
        // select col1, col2 from table
        $sql = $this->obj;
        $sql->select('col1, col2');
        $sql->table('table1');
        $this->assertEquals('select col1, col2 from table1',$sql);
    }

}

?>