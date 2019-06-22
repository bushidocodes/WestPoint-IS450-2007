<?php
require_once("EquipmentManager.php");
require_once("Laptop.php");
$laptop = new Laptop();
$laptop->Add();
$laptop->mgrAddLaptop('333666','1','2006-12-12','test','Linux');
?>