<?php
require '../src/bootstrap.php';

$pdo = get_pdo();
$model = new \Calendar\Events($pdo);
$errors = [];
try {
    /** @var \Calendar\Event $event */
    $event = $model->find($_GET['id'] ?? null);
} catch (Exception $e) {
    e404();
} catch (Error $e) {
    e404();
}

$data = [
    'name' => $event->getName(),
    'description' => $event->getDescription(),
    'date' => $event->getStartedAt()->format('Y-m-d'),
    'start' => $event->getStartedAt()->format('H:i'),
    'end' => $event->getEndedAt()->format('H:i')
];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;

    $validator = new \Calendar\EventValidator();
    $errors = $validator->validates($_POST);

    if (empty($errors)) {
        $event = $model->hydrate($event, $data);
        $model->update($event);

        header('Location: /?success=1');
        exit();
    }
}

render('header', ['title' => $event->getName()])
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Edit Event
                <small><?= h($event->getName()); ?></small>
            </h1>

            <?php if ($errors): ?>
                <div class="alert alert-danger">Merci de corriger les erreurs.</div>
            <?php endif; ?>

            <form action="" method="post" class="form">

                <?php render('calendar/form', compact('errors', 'data')) ?>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php render('footer'); ?>
