<?php
// Seamus Johnston, 2018

declare(strict_types=1);

// Constants -- could be moved to a separate file
$ROWS = 3;       // people to your left and right
$COLUMNS = 11;   // people behind and in front of you

// Output strings grouped for easier extraction in future l18n
$INTIAL_INPUT_ERROR = "Inital reservations must be of the form 'R3C5 R2C8 R4C5'.";
$GROUP_RESERVATION_INPUT_ERROR = "Additional reservations must be the party number,
entered numerically, one per line.";
$PARSE_SEAT_VALUE_ERROR = "Seat must be given as R#C#, eg 'R3C15'";

/* Return an array(row, column) when given string in the form 'R#C#' */
function parseSeat($seat)
{
    global $PARSE_SEAT_VALUE_ERROR;
    preg_match('/\s*[Rr]([0-9]+)[Cc]([0-9]+)\s*/', $seat, $match);
    if (!isset($match[1]) || !isset($match[2])) {
        throw new InvalidArgumentException($PARSE_SEAT_VALUE_ERROR);
    }
    return array($match[1],$match[2]);
}

/* Converts a string of reservations into a list of tuples.
   "R1C3 R2C5 R3C6" -> [(1,3),(2,5),(3,6)] */
function parseIntialString($ir)
{
    global $INTIAL_INPUT_ERROR;
    global $PARSE_SEAT_VALUE_ERROR;
    if ($ir == '') {
        return null;
    }
    $rc_tuples = array();
    if (preg_match('/[^RCrc0-9\s]+/',$ir)) {
        throw new InvalidArgumentException($INTIAL_INPUT_ERROR);
    }
    $rl = preg_split('/\s+/', $ir);
    foreach ($rl as $res) {
        $rc_tuples[] = parseSeat($res);
    }
    return $rc_tuples;
}

/* Convert a list of seat tuples into a formated string. */
function parseOutput($res)
{
    if ($res[0][1] == $res[1][1]) {
        return vsprintf("R%sC%s\n",$res[0]);
    } else {
        return sprintf("R%sC%s - R%sC%s\n",$res[0][0],$res[0][1],$res[1][0],$res[1][1]);
    }
}

/* Reserve a group of seats or return null. */
function additionalReservation($chart,$n_seats)
{
    if (is_numeric($n_seats)) {
        return $chart->reserve($n_seats);
    } else {
        echo $GROUP_RESERVATION_INPUT_ERROR;
        return null;
    }
}