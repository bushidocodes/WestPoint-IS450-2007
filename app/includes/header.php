<?php
	if(session_status() === PHP_SESSION_NONE) session_start();
	$pageTitle = $pageTitle ?? 'IS450 Equipment Management System';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php print(htmlspecialchars($pageTitle)); ?> &mdash; IS450 Equipment Management System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f4f0; }
        #banner { background: #2d2d2d; color: #f4f4f0; padding: 12px 24px; }
        #banner h1 { margin: 0; font-size: 1.3em; }
        #banner h1 a { color: #f4f4f0; text-decoration: none; }
        #nav { background: #4a4a44; padding: 6px 24px; }
        #nav a { color: #fff; text-decoration: none; margin-right: 18px; font-size: 0.95em; }
        #nav a:hover { text-decoration: underline; }
        #content { padding: 18px 24px; }
        h2 { margin-top: 0; }
        table { border-collapse: collapse; background: #fff; }
        th, td { padding: 4px 8px; }
        th { background: #e8e8e0; }
        #smallTable { font-size: 0.9em; }
        form.stacked label { display: block; margin: 8px 0 2px; font-weight: bold; }
        form.stacked input[type=text], form.stacked input[type=date], form.stacked select { width: 260px; }
        form.stacked input[type=submit] { margin-top: 12px; }
        .message { background: #e0f0d8; border: 1px solid #9c9; padding: 8px 12px; margin-bottom: 12px; }
        .error { background: #f5dada; border: 1px solid #c99; padding: 8px 12px; margin-bottom: 12px; }
        fieldset { margin-top: 12px; width: 290px; }
    </style>
</head>
<body>
<div id="banner"><h1><a href="/app/">IS450 Equipment Management System</a></h1></div>
<div id="nav">
    <a href="/app/">Dashboard</a>
    <a href="/app/people/">People</a>
    <a href="/app/people/search.php">Find Person</a>
    <a href="/app/people/add.php">Add Person</a>
    <a href="/app/equipment/">Equipment</a>
    <a href="/app/equipment/search.php">Find Equipment</a>
    <a href="/app/equipment/add.php">Add Equipment</a>
    <a href="/app/reservations/">Reservations</a>
    <a href="/app/reservations/checkout.php">Check Out</a>
</div>
<div id="content">
