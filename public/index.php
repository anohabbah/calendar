
<?php
require '../src/bootstrap.php';

$month = new Calendar\Month($_GET['month'] ?? null, $_GET['year'] ?? null);
$pdo = get_pdo();
$events = new \Calendar\Events($pdo);
$start = $month->getStartingDay();
$weeks = $month->getWeeks();
$last_monday = $start->format('N') === '1' ? $start : $start->modify("last monday");
$end = (clone $start)->modify('+'. (6 + 7 * ($weeks - 1)) . ' days');
$events = $events->getEventsBetweenByDay($start, $end);

render('header')
?>

<div class="calendar">
    <div class="d-flex flex-row align-items-center justify-content-between">
        <h1><?= $month->toString(); ?></h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Event successfully created.</div>
        <?php endif; ?>

        <div class="mr-3">
            <a href="/?month=<?= $month->previousMonth()->month ?>&year=<?= $month->previousMonth()->year ?>"
               class="btn btn-primary">&lt;</a>
            <a href="/?month=<?= $month->nextMonth()->month ?>&year=<?= $month->nextMonth()->year ?>"
               class="btn btn-primary">&gt;</a>
        </div>
    </div>

    <table class="calendar__table calendar__table--<?= $weeks; ?>weeks">
        <?php for ($i = 0; $i < $weeks; ++$i): ?>
            <tr>
                <?php
                foreach ($month->days as $k => $day):
                    $date = (clone $last_monday)->modify("+" . ($k + $i * 7) . " days");
                    $eventsForDay = $events[$date->format('Y-m-d')] ?? [];
                    $isToday = date('Y-m-d') === $date->format('Y-m-d');
                    ?>
                    <td<?= $month->withinMonth($date) ? '' : ' class="calendar__othermonth"' ?><?= !$isToday ? '' : ' class="is-today"' ?>>
                        <?php if ($i === 0): ?>
                            <div class="calendar__weekday"><?= $day; ?></div>
                        <?php endif; ?>
                        <a class="calendar__day" href="/add.php?date=<?= $date->format('Y-m-d'); ?>"><?= $date->format('d'); ?></a>
                        <?php foreach ($eventsForDay as $event): ?>
                            <div class="calendar__event">
                                <?= (new DateTime($event['started_at']))->format('H:i') ?> -
                                <a href="/edit.php?id=<?= $event['id'] ?>"><?= h($event['name']) ?></a>
                            </div>
                        <?php endforeach; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endfor; ?>
    </table>

    <a href="/add.php" class="calendar__button">+</a>
</div>

<?php require '../views/footer.php' ?>
