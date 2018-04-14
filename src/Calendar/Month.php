<?php

namespace Calendar;

class Month
{
    public $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

    private $months = ['Janvier', 'fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
        'Octobre', 'Novembre', 'Décembre'];

    /**
     * @var int|null
     */
    public $month;

    /**
     * @var int|null
     */
    public $year;

    /**
     * Month constructor.
     * @param int|null $month Le mois compris entre 1 et 12
     * @param int|null $year L'année
     */
    public function __construct(?int $month = null, ?int $year = null)
    {
        if ($month === null || $month < 1 || $month > 12)
            $month = intval(date('m'));

        if ($year === null)
            $year = intval(date('Y'));

        $this->month = $month;
        $this->year = $year;
    }

    /**
     * Retourne le mois en toute lettre (ex.: Mars 2018)
     * @return string
     */
    public function toString(): string
    {
        return "{$this->months[$this->month - 1]}  {$this->year}";
    }

    /**
     * Retourne le nombre de seamine dans le mois.
     * @return int
     * @throws \Exception
     */
    public function getWeeks(): int
    {
        $start = $this->getStartingDay();
        $end = $start->modify("+1 month -1 day");

        $endWeek = intval($end->format('W'));
        $startWeek = intval($start->format('W'));
        if ($endWeek === 1)
            $endWeek = intval($end->modify('-7 days')->format('W')) + 1;

        $weeks = $endWeek - $startWeek + 1;

        if ($weeks < 0)
            $weeks = $endWeek;

        return $weeks;
    }

    /**
     * Renvoie le premier jour du mois.
     * @return \DateTimeInterface
     * @throws \Exception
     */
    public function getStartingDay(): \DateTimeInterface
    {
        return new \DateTimeImmutable("{$this->year}-{$this->month}-01");
    }

    /**
     * @param \DateTimeInterface $date
     * @return bool
     * @throws \Exception
     */
    public function withinMonth(\DateTimeInterface $date): bool
    {
        return $this->getStartingDay()->format('Y-m') === $date->format('Y-m');
    }

    /**
     * @return Month
     */
    public function nextMonth(): Month
    {
        $month = $this->month + 1;
        $year = $this->year;

        if ($month > 12) {
            $month = 1;
            $year += $year;
        }

        return new Month($month, $year);
    }

    /**
     * @return Month
     */
    public function previousMonth(): Month
    {
        $month = $this->month - 1;
        $year = $this->year;

        if ($month <= 0) {
            $month = 12;
            $year -= $year;
        }

        return new Month($month, $year);
    }
}