
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

<div class="d-flex flex-row align-items-center justify-content-between">
    <h1><?= $month->toString(); ?></h1>
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
                ?>
                <td<?= $month->withinMonth($date) ? '' : ' class="calendar__othermonth"' ?>>
                    <?php if ($i === 0): ?>
                        <div class="calendar__weekday"><?= $day; ?></div>
                    <?php endif; ?>
                    <div class="calendar__day"><?= $date->format('d'); ?></div>
                    <?php foreach ($eventsForDay as $event): ?>
                    <div class="calendar__event">
                        <?= (new DateTime($event['started_at']))->format('H:i') ?> -
                        <a href="/event.php?id=<?= $event['id'] ?>"><?= h($event['name']) ?></a>
                    </div>
                    <?php endforeach; ?>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endfor; ?>
</table>

<?php require '../views/footer.php' ?>
