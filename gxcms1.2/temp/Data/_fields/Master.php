<?php
return array (
  0 => 'id',
  1 => 'user',
  2 => 'pwd',
  3 => 'usertype',
  4 => 'logincount',
  5 => 'loginip',
  6 => 'logintime',
  '_autoinc' => true,
  '_pk' => 'id',
  '_type' => 
  array (
    'id' => 'tinyint(3) unsigned',
    'user' => 'varchar(50)',
    'pwd' => 'char(32)',
    'usertype' => 'varchar(100)',
    'logincount' => 'smallint(5)',
    'loginip' => 'varchar(40)',
    'logintime' => 'int(11)',
  ),
);
?>