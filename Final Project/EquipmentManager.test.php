<?php
require_once("Equipment.php");
require_once("Laptop.php");
require_once("EquipmentManager.php");

$equip = new Equipment();

// Add a laptop via the domain-class interface
$result = $equip->addEquipment('333666', '0', '2006-12-12', 'test', 'laptop', 'Linux');
if(!$result)
    print("FAILED - addEquipment (laptop)<br/>");
else
    print("PASSED - addEquipment (laptop)<br/>");

// Verify it can be retrieved
$found = $equip->searchForEquipmentBySerialNumber('333666');
if(!$found || $found->getSerialNumber() !== '333666')
    print("FAILED - searchForEquipmentBySerialNumber after add<br/>");
else
    print("PASSED - searchForEquipmentBySerialNumber after add<br/>");

// Clean up
$deleted = $equip->deleteEquipment('333666');
if(!$deleted)
    print("FAILED - deleteEquipment (cleanup)<br/>");
else
    print("PASSED - deleteEquipment (cleanup)<br/>");
?>
