<?php

/**
 * Copyright 2017 - Abbah Anoh
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
 * Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
 * OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

require '../src/bootstrap.php';
render('header', ['title' => 'Créer un évènement']);

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;

    $validator = new \Calendar\EventValidator();
    $errors = $validator->validates($_POST);

    if (empty($errors)) {
        $event = new \Calendar\Event();
        $event->setName($data['name']);
        $event->setDescription($data['description']);
        $event->setStartedAt(DateTime::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . $data['start'])->format('y-m-d H:i:s'));
        $event->setEndedAt(DateTime::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . $data['end'])->format('y-m-d H:i:s'));
        $model = new \Calendar\Events(get_pdo());
        $model->create($event);

        header('Location: /?success=1');
        exit();
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Créer un évènement</h1>

            <?php if ($errors): ?>
                <div class="alert alert-danger">Merci de corriger les erreurs.</div>
            <?php endif; ?>

            <form action="" method="post" class="form">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Titre</label>
                            <input type="text" id="name" name="name"
                                   class="form-control<?= isset($errors['name']) ? ' is-invalid' : '' ?>" required
                                   value="<?= isset($data['name']) ? h($data['name']) : ''; ?>">
                            <?php if (isset($errors['name'])): ?>
                                <p class="invalid-feedback"><?= ($errors['name']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" id="date" name="date"
                                   class="form-control<?= isset($errors['date']) ? ' is-invalid' : '' ?>" required
                                   value="<?= isset($data['date']) ? h($data['date']) : ''; ?>">
                            <?php if (isset($errors['date'])): ?>
                                <p class="invalid-feedback"><?= ($errors['date']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start">Heure de demarrage</label>
                            <input type="time" id="start" name="start"
                                   class="form-control<?= isset($errors['start']) ? ' is-invalid' : '' ?>"
                                   placeholder="HH:MM" required value="<?= isset($data['start']) ? h($data['start']) : ''; ?>">
                            <?php if (isset($errors['start'])): ?>
                                <p class="invalid-feedback"><?= ($errors['start']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end">Heure de fin</label>
                            <input type="time" id="end" name="end"
                                   class="form-control<?= isset($errors['end']) ? ' is-invalid' : '' ?>"
                                   placeholder="HH:MM" required value="<?= isset($data['end']) ? h($data['end']) : ''; ?>">
                            <?php if (isset($errors['end'])): ?>
                                <p class="invalid-feedback"><?= ($errors['end']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description"
                                      class="form-control<?= isset($errors['description']) ? ' is-invalid' : '' ?>"
                                      required><?= isset($data['description']) ? h($data['description']) : ''; ?></textarea>
                            <?php if (isset($errors['description'])): ?>
                                <p class="invalid-feedback"><?= ($errors['description']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php render('footer') ?>
