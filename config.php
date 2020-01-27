<?php
/* Omdat wij PhpMyAdmin gebruiken voor opslag van account gegevens en biedingen is het belangrijk dat de site wel kan verbinden met de database waarin al deze gegevens staan, dit gebeurt d.m.v. deze config file */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'herenusr');
define('DB_PASSWORD', 'oVqp61@6');
define('DB_NAME', 'herenhuis');

/* Verbinding maken met de database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);