<?php
/**
 * Created by IntelliJ IDEA.
 * User: dropbird
 * Date: 12/04/2018
 * Time: 12:19
 */

require '../vendor/autoload.php';

/**
 * For debugging purpose
 * @param mixed ...$vars
 */
function dd(...$vars) {
    foreach ($vars as $var) {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

function get_pdo(): PDO {
    return new \PDO('mysql:host=127.0.0.1;port=3307;dbname=tuto_calendar', 'root', '', [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
    ]);
}

function h(?string $value = null): string {
    $str = '';

    if ($value !== null)
        $str = $value;

    return htmlentities($str);
}

function e404() {
    require '../public/404.php';
    exit();
}

function render(string $view, array $params = []) {
    extract($params);

    include "../views/{$view}.php";
}
