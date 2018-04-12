<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calendar Tutorial</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="css/calendar.css">
</head>
<body>
<nav class="navbar navbar-dark bg-primary mb-3">
    <a href="/" class="navbar-brand">My Calendar</a>
</nav>

<?php
require "../src/Date/Month.php";
$month = new App\Date\Month($_GET['month'] ?? null, $_GET['year'] ?? null);
$last_monday = $month->getStartingDay()->modify("last monday");
?>

<div class="d-flex flex-row align-items-center justify-content-between ms-3">
    <h1><?= $month->toString(); ?></h1>
    <div>
        <a href="/?month=<?= $month->previousMonth()->month ?>&year=<?= $month->previousMonth()->year ?>" class="btn btn-primary">&lt;</a>
        <a href="/?month=<?= $month->nextMonth()->month ?>&year=<?= $month->nextMonth()->year ?>" class="btn btn-primary">&gt;</a>
    </div>
</div>

<table class="calendar__table calendar__table--<?= $month->getWeeks(); ?>weeks">
    <?php for ($i = 0; $i < $month->getWeeks(); ++$i): ?>
        <tr>
            <?php
            foreach ($month->days as $k => $day):
                $date = (clone $last_monday)->modify("+" . ($k + $i * 7) . " days");
                ?>
                <td<?= $month->withinMonth($date) ? '' : ' class="calendar__othermonth"' ?>>
                    <?php if ($i === 0): ?>
                        <div class="calendar__weekday"><?= $day; ?></div>
                    <?php endif; ?>
                    <div class="calendar__day"><?= $date->format('d'); ?></div>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endfor; ?>
</table>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</body>
</html>