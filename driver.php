<?php
// Seamus Johnston, 2018

declare(strict_types=1);

require_once "SeatingChart.php";
require_once "driver-helpers.php";

/* Create the SeatingChart ojbect */
$chart = new SeatingChart($ROWS,$COLUMNS);

/* Get initial reservations */
$initial_reservations = trim(fgets(STDIN));
try {
    $reservation_list = parseIntialString($initial_reservations);
} catch (InvalidArgumentException $e) {
    echo $e;
}
if ($reservation_list) {
    $chart->initialize($reservation_list);
}

/* Obtain further reservations */
while ($ln = fgets(STDIN)) {
    $number_of_seats = (int) trim($ln);
    if ($number_of_seats == 0) {
        echo $GROUP_RESERVATION_INPUT_ERROR;
        continue;
    }
    $seats_reserved = additionalReservation($chart,$number_of_seats);
    if ($seats_reserved) {
        echo parseOutput($seats_reserved);
    } else {
        echo "Not Available\n";
    }
}
echo "$chart->number_available\n";