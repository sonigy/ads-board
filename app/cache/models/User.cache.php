<?php
return array("#tableName"=>"User","#primaryKeys"=>array("id"),"#manyToOne"=>array(),"#fieldNames"=>array("id"=>"id","email"=>"email","name"=>"name","lastname"=>"lastname","password"=>"password","phone"=>"phone","role"=>"role","adss"=>"adss"),"#fieldTypes"=>array("id"=>"int(11)","email"=>"varchar(320)","name"=>"varchar(64)","lastname"=>"varchar(64)","password"=>"char(32)","phone"=>"varchar(25)","role"=>"varchar(10)","adss"=>"mixed"),"#nullable"=>array(),"#notSerializable"=>array("adss"),"#transformers"=>array(),"#accessors"=>array("id"=>"setId","email"=>"setEmail","name"=>"setName","lastname"=>"setLastname","password"=>"setPassword","phone"=>"setPhone","role"=>"setRole","adss"=>"setAdss"),"#oneToMany"=>array("adss"=>array("mappedBy"=>"user","className"=>"models\\Ads")));
