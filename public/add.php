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

$errors = [];
$data = [
    'date' => $_GET['date'] ?? date('Y-m-d'),
    'start' => date('H:i'),
    'end' => date('H:i')
];

$validator = new \App\Validator($data);
if (!$validator->validate('date', 'date')) {
    $data['date'] = date('Y-m-d');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;

    $validator = new \Calendar\EventValidator();
    $errors = $validator->validates($_POST);

    if (empty($errors)) {
        $model = new \Calendar\Events(get_pdo());
        $event = $model->hydrate(new \Calendar\Event(), $data);
        $model->create($event);

        header('Location: /?success=1');
        exit();
    }
}

render('header', ['title' => 'Créer un évènement']);
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Créer un évènement</h1>

            <?php if ($errors): ?>
                <div class="alert alert-danger">Merci de corriger les erreurs.</div>
            <?php endif; ?>

            <form action="" method="post" class="form">

                <?php render('calendar/form', compact('errors', 'data')) ?>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php render('footer') ?>
