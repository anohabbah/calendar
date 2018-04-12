
<?php
require '../src/bootstrap.php';
require '../src/Calendar/Event.php';

if (! isset($_GET['id']))
    header('Location: 404.php');

$pdo = get_pdo();
$event = new \Calendar\Event($pdo);
$event = $event->find($_GET['id']);

require '../views/header.php'
?>


<?php require '../views/footer.php' ?>
