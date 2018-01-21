<?php
// Seamus Johnston, 2018

declare(strict_types=1);

final class SeatingChart
{
    // Error strings grouped for easier extraction in future l18n
    private $RESERVE_TYPE_ERROR = "Reservation must be integer or string";
    private $INITIALIZATION_ERROR = "Seat (%s,%s) could not be reserved.";

    public $sold_out;
    private $number_reserved;
    private $number_available;
    private $rows;
    private $cols;
    private $reserved_seats;

    public function __construct(int $rows, int $columns)
    {
        $this->rows = $rows;
        $this->cols = $columns;
        $this->sold_out = false;
        $this->number_reserved = 0;
        $this->number_available = $rows * $columns;
        $this->reserved_seats = array();
    }

    /* Reserve seats from an array of (row, column) tuples. */
    public function initialize(array $res_lst)
    {
        // NB: if the SeatingChart object is presisted to a database,
        // these reservations must be inside a transaction
        foreach ($res_lst as $res) {
            if (!$this->individual($res)) {
                throw new InvalidArgumentException(
                    vsprintf($this->INITIALIZATION_ERROR,$res)
                );
            }
        }
    }

    /* Hash function. */
    private function getKey(array $seat)
    {
        $r = $seat[0];
        $c = $seat[1];
        return $c * 1000 + $r;
    }

    /* Return the Manhattan Distance from stage center of a given seat. */
    private function getTaxi(array $seat) : int
    {
        $r = $seat[0];
        $c = $seat[1];
        $middle = $this->cols % 2 == 0 ? $this->cols / 2 + .5 : floor($this->cols / 2) + 1;
        $taxi = (int) round(abs($c - $middle) + abs($r - 1));
        return $taxi;
    }

    /* Reserve seat in chart if not already reserved. */
    private function individual(array $seat)
    {
        $key = $this->getKey($seat);
        // Safety tests
        if ($this->sold_out == true) {
            return null;
        }
        if ($seat[0] > $this->rows or $seat[1] > $this->cols) {
            return null;
        }
        if ($seat[0] < 1 or $seat[1] < 1) {
            return null;
        }
        // Make reservation
        if (!array_key_exists($key, $this->reserved_seats)) {
            $this->reserved_seats[$key] = 1;
            $this->number_reserved += 1;
            $this->number_available -= 1;
            if ($this->number_available == 0) {
                $this->sold_out == true;
            }
            return [$seat];
        } else {
          return null;
        }
    }

    /* Find and reserve a group of seats, given a group size.
    Return null if unable to find enough consecutive seats on one row.
    If there are multiple candidates, pick the best one by Manhattan Distance. */
    private function findSeats(int $sought)
    {
        if ($sought > $this->cols or $this->sold_out) {
            return null;
        }

        // Traverse each row, stage to rear.
        // Record blocks of unreserved consecutive seats.
        $candidates = array();
        for ($row = 1; $row <= $this->rows; $row++) {
            $seats_to_reserve = array();
            for ($col = 1; $col <= $this->cols; $col++) {
                $seat = array($row,$col);
                // Do not take someone else's seat
                if ($this->reserved($seat)) {
                    $seats_to_reserve = array();
                } else {
                    $seats_to_reserve[] = $seat;
                }
                // Have we found enough seats?
                if (count($seats_to_reserve) == $sought)
                {
                    // Measure how far from stage center
                    $taxi_left = $this->getTaxi($seats_to_reserve[0]);
                    end($seats_to_reserve);
                    $r_idx = key($seats_to_reserve);
                    reset($seats_to_reserve);
                    $taxi_right = $this->getTaxi($seats_to_reserve[$r_idx]);
                    $taxi_dist = $taxi_right >= $taxi_left ? $taxi_right : $taxi_left;
                    // Record these seats for later
                    if (!array_key_exists($taxi_dist, $candidates)) {
                        $candidates[$taxi_dist] = $seats_to_reserve;
                    }
                    // Make count($seats_to_reserve) != sought
                    array_shift($seats_to_reserve);
                }
            }
        }

        // Take the best group of seats (if any) and reserve them.
        if ($candidates) {
            $reservation_list = $candidates[min(array_keys($candidates))];
            foreach ($reservation_list as $res) {
                // NB: if the SeatingChart object is presisted to a database,
                // these reservations must be inside a transaction
                $this->individual($res);
            }
            end($reservation_list);
            $r_idx = key($reservation_list);
            reset($reservation_list);
            return array($reservation_list[0],$reservation_list[$r_idx]);
        } else {
            return null;
        }
    }

    /* Reserve one seat or attempt to find reservations for a given number.
    Return null if reservation fails otherwise
    return the reserved seats as a sorted list */
    public function reserve($seats)
    {
        if (is_array($seats)) {
            $reservations = $this->individual($seats);
        } elseif (is_numeric($seats)) {
            $reservations = $this->findSeats($seats);
        }
        return $reservations;
    }

    /* Is seat resevered? */
    public function reserved(array $seat)
    {
        $key = $this->getKey($seat);
        return array_key_exists($key,$this->reserved_seats);
    }
}