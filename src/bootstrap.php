<?php
/**
 * Created by IntelliJ IDEA.
 * User: dropbird
 * Date: 12/04/2018
 * Time: 12:19
 */

function dd(...$vars) {
    foreach ($vars as $var) {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

function get_pdo() {
    return new \PDO('mysql:host=127.0.0.1;dbname=tuto_calendar', 'root', 'root', [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
    ]);
}