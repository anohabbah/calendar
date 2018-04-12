<?php
/**
 * Created by IntelliJ IDEA.
 * User: dropbird
 * Date: 12/04/2018
 * Time: 10:53
 */

namespace Calendar;


class Events
{

    /**
     * @var \PDO
     */
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Recupère tous les evenements entre 2 dates.
     *
     * @param \DateTime $start date de debut
     * @param \DateTime $end date de fin
     * @return array Un tabeau representant l'ensemble des evenements.
     */
    public function getEventsBetween(\DateTime $start, \DateTime $end): array
    {
        $sql = "SELECT * FROM events WHERE started_at BETWEEN '{$start->format('Y-m-d 00:00:00')}' AND '{$end->format('Y-m-d 23:59:59')}'";
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll();
    }

    /**
     * Recupère tous les evenements entre 2 dates formaté par jour..
     *
     * @param \DateTime $start
     * @param \DateTime $end
     * @return array
     */
    public function getEventsBetweenByDay(\DateTime $start, \DateTime $end): array
    {
        $events = $this->getEventsBetween($start, $end);
        $days = [];

        foreach ($events as $event) {
            $date = explode(' ', $event['started_at'])[0];
            if (!isset($days[$date])) {
                $days[$date] = [$event];
            } else {
                $days[$date][] = $event;
            }
        }

        return $days;
    }

    /**
     * Recupere un event.
     *
     * @param int $id identifiant de l'event à recuperer
     * @return Event
     * @throws \Exception
     */
    public function find(int $id): Event
    {
        $stmt = $this->pdo->query("SELECT * FROM events WHERE id = {$id}");
        $stmt->setFetchMode(\PDO::FETCH_CLASS, Event::class);

        $result = $stmt->fetch();
        if ($result === false) {
            throw new \Exception("Event inexistant.");
        }

        return $result;
    }

    public function create(Event $event): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO events(name, description, started_at, ended_at) VALUES (:name, :description, :started_at, :ended_at)');
        return $stmt->execute($event->toArray());
    }
}