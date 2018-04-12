
<?php
require '../src/bootstrap.php';

if (! isset($_GET['id']))
    header('Location: 404.php');

$pdo = get_pdo();
$model = new \Calendar\Events($pdo);
try {
    /** @var \Calendar\Event $event */
    $event = $model->find($_GET['id']);
} catch (Exception $e) {
    e404();
}

render('header', ['title' => $event->getName()])
?>

<div class="container-event">
    <h1 class="name"><?=  h($event->getName()); ?></h1>
    <ul class="details">
        <li>Date: <?= $event->getStartedAt()->format('d/m/Y'); ?></li>
        <li>Heure de debut: <?= $event->getStartedAt()->format('H:i'); ?></li>
        <li>Heure de fin: <?= $event->getEndedAt()->format('H:i'); ?></li>
    </ul>
    <p class="desc"><?= h($event->getDescription()); ?></p>
</div>

<?php require '../views/footer.php' ?>
