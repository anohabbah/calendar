
<?php
require '../src/bootstrap.php';

$pdo = get_pdo();
$events = new \Calendar\Events($pdo);
try {
    $start = new DateTimeImmutable('first day of january');
} catch (Exception $e) {
}
$end = $start->modify('last day of december')->modify('+1 day');
$events = $events->getEventsBetween($start, $end);
?>
id;nom;debut;fin;

<?php foreach ($events as $event): ?>
<?= $event->getId() .';"'.$event->getName(). '";' .$event->getStartedAt()->format('Y-m-d H:i:s'). ';' .$event->getEndedAt()->format('Y-m-d H:i:s') .";" ?>
<?php endforeach; ?>
