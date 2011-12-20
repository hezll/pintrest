<?php
return array (
  0 => 'id',
  1 => 'pid',
  2 => 'oid',
  3 => 'mid',
  4 => 'status',
  5 => 'cname',
  6 => 'cfile',
  7 => 'ctpl',
  8 => 'cwebsite',
  9 => 'ctitle',
  10 => 'ckeywords',
  11 => 'cdescription',
  '_autoinc' => true,
  '_pk' => 'id',
  '_type' => 
  array (
    'id' => 'smallint(5) unsigned',
    'pid' => 'smallint(5)',
    'oid' => 'smallint(5)',
    'mid' => 'tinyint(2)',
    'status' => 'tinyint(1)',
    'cname' => 'char(20)',
    'cfile' => 'varchar(20)',
    'ctpl' => 'char(20)',
    'cwebsite' => 'varchar(255)',
    'ctitle' => 'varchar(50)',
    'ckeywords' => 'varchar(255)',
    'cdescription' => 'varchar(255)',
  ),
);
?>