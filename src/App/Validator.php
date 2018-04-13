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

namespace App;


class Validator
{
    private $data;

    protected $errors = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     */
    public function validates(array $data)
    {
        $this->errors = [];
        $this->data = $data;
    }

    public function validate(string $field, string $rule, ...$parameters)
    {
        if (!isset($this->data[$field])) {
            $this->errors[$field] = "Le champ $field est obligatoire.";
            return false;
        } else
            return call_user_func([$this, $rule], $field, ...$parameters);
    }

    public function min(string $field, int $length): bool
    {
        $bool = true;
        if (mb_strlen($this->data[$field]) < $length) {
            $bool = false;
            $this->errors[$field] = "Le champ $field doit avoir au moins $length caractères.";
        }

        return $bool;
    }

    public function date(string $field): bool
    {
        $bool = true;
        if (!\DateTime::createFromFormat('Y-m-d', $this->data[$field])) {
            $bool = false;
            $this->errors[$field] = "Le champ $field n'est pas une date valide.";
        }

        return $bool;
    }

    /**
     * Verirfie que $field est une heure valide.
     * @param string $field
     * @return bool
     */
    public function time(string $field): bool
    {
        $bool = true;
        if (!\DateTime::createFromFormat('H:i', $this->data[$field])) {
            $bool = false;
            $this->errors[$field] = "Le champ $field n'est pas une heure valide.";
        }

        return $bool;
    }

    /**
     * Vérifie $fiels et $needle sont des heures valides, et que $field est une heure qui precede le $needle.
     * @param $field
     * @param $needle
     * @return bool
     */
    public function before($field, $needle): bool
    {
        $bool = false;
        if ($this->time($field) && $this->time($needle)) {
            $start = \DateTime::createFromFormat('H:i', $this->data[$field]);
            $end = \DateTime::createFromFormat('H:i', $this->data[$needle]);

            if ($start->getTimestamp() > $end->getTimestamp()) {
                $this->errors[$field] = "Le champ $field doit inférieur à la valeur du champ $needle";
                $bool = false;
            } else {
                $bool = true;
            }
        }

        return $bool;
    }

    /**
     * Exige que la variable passée en paramètre existe.
     *
     * @param string $field variable à vérifier.
     * @return bool <code>true</code> si la variable existe et no vide, <code>false</code> sinon
     */
    public function required($field)
    {
        $bool = true;
        if (empty($this->data[$field])) {
            $bool = false;
            $this->errors[$field] = "Le champ $field est obligatoire.";
        }

        return $bool;
    }
}