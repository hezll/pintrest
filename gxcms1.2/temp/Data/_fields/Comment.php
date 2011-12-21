<?php
return array (
  0 => 'id',
  1 => 'did',
  2 => 'mid',
  3 => 'uid',
  4 => 'content',
  5 => 'up',
  6 => 'down',
  7 => 'ip',
  8 => 'addtime',
  9 => 'status',
  '_autoinc' => true,
  '_pk' => 'id',
  '_type' => 
  array (
    'id' => 'mediumint(8) unsigned',
    'did' => 'mediumint(8)',
    'mid' => 'tinyint(2)',
    'uid' => 'mediumint(8)',
    'content' => 'varchar(255)',
    'up' => 'mediumint(8)',
    'down' => 'mediumint(8)',
    'ip' => 'varchar(20)',
    'addtime' => 'int(11)',
    'status' => 'tinyint(1)',
  ),
);
?>