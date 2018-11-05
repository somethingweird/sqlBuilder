<?php

require __DIR__ . "/vendor/autoload.php";

// sample
$sql = new NYIT\sqlB();
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
