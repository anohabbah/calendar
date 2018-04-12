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

namespace Calendar;


class Event
{
    private $id;

    private $name;

    private $description;

    private $started_at;

    private $ended_at;

    /**
     * @return mixed
     */
    public function getEndedAt(): \DateTime
    {
        return new \DateTime($this->ended_at);
    }

    /**
     * @param mixed $ended_at
     */
    public function setEndedAt(string $ended_at): void
    {
        $this->ended_at = $ended_at;
    }

    /**
     * @return mixed
     */
    public function getStartedAt(): \DateTime
    {
        return new \DateTime($this->started_at);
    }

    /**
     * @param mixed $started_at
     */
    public function setStartedAt(string $started_at): void
    {
        $this->started_at = $started_at;
    }

    /**
     * @return mixed
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'started_at' => $this->getStartedAt()->format('Y-m-d H:i:s'),
            'ended_at' => $this->getEndedAt()->format('Y-m-d H:i:s')
        ];
    }
}