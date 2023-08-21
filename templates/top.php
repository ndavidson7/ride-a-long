<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Nicholas Davidson">
    <meta name="description" content="Ride-A-Long: Find peers with whom to carpool long-distance - <?= $title ?>">
    <meta name="keywords" content="Ride-a-long, ridealong, ride, uva, carpool, hoosdriving, hoosriding">
    <title>Ride-A-Long - <?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <?php
    if (isset($styles)) {
        foreach ($styles as $style) { ?>
            <link rel="stylesheet" type="text/css" href="/styles/<?= $style ?>.css">
    <?php
        }
    }
    ?>
    <noscript>JavaScript must be enabled to use Ride-A-Long.</noscript>
    <?php
    if (isset($scripts)) {
        foreach ($scripts as $script) {
            echo $script;
        }
    }
    ?>
</head>
<?php
if ($navbar === true) include "templates/navbar.php";
else include "templates/largeheader.php";
