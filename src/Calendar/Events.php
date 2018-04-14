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
     * @param \DateTimeInterface $start date de debut
     * @param \DateTimeInterface $end date de fin
     * @return array Un tabeau representant l'ensemble des evenements.
     */
    public function getEventsBetween(\DateTimeInterface $start, \DateTimeInterface $end): array
    {
        $sql = "SELECT * FROM events WHERE started_at BETWEEN '{$start->format('Y-m-d 00:00:00')}' AND '{$end->format('Y-m-d 23:59:59')}' ORDER BY started_at ASC";
        $stmt = $this->pdo->query($sql);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, Event::class);

        return $stmt->fetchAll();
    }

    /**
     * Recupère tous les evenements entre 2 dates formaté par jour..
     *
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     * @return array Event[]
     */
    public function getEventsBetweenByDay(\DateTimeInterface $start, \DateTimeInterface $end): array
    {
        $events = $this->getEventsBetween($start, $end);
        $days = [];

        foreach ($events as $event) {
            $date = $event->getStartedAt()->format('Y-m-d');
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

    /**
     * @param Event $event
     * @param array $payload
     * @return Event
     */
    public function hydrate(Event $event, array $payload): Event
    {
        $event->setName($payload['name']);
        $event->setDescription($payload['description']);
        $event->setStartedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i', $payload['date'] . ' ' . $payload['start'])->format('y-m-d H:i:s'));
        $event->setEndedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i', $payload['date'] . ' ' . $payload['end'])->format('y-m-d H:i:s'));

        return $event;
    }

    /**
     * @param Event $event
     * @return bool
     */
    public function create(Event $event): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO events(name, description, started_at, ended_at) VALUES (:name, :description, :started_at, :ended_at)');
        return $stmt->execute($event->toArray());
    }

    /**
     * @param Event $event
     * @return bool
     */
    public function update(Event $event): bool
    {
        $stmt = $this->pdo->prepare('UPDATE events SET name = :name, description = :description,  started_at = :started_at, ended_at = :ended_at WHERE id = :id');
        return $stmt->execute(array_merge($event->toArray(), ['id' => $event->getId()]));
    }
}