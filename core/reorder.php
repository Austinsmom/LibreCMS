<?php
require'db.php';
$i=0;
foreach($_POST['l']as$id){
  $s=$db->prepare("UPDATE menu SET ord=:ord WHERE id=:id");
  $s->execute(array(':ord'=>$i,':id'=>$id));
  $i++;
}
