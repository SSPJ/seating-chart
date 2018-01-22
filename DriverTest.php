<?php
// Seamus Johnston, 2018

declare(strict_types=1);

require "SeatingChart.php";
require "driver-helpers.php";

class DriverTest extends PHPUnit\Framework\TestCase {

    ### public function parseSeat(seat) ###
    public function testParseSeatGivenNonsense()
    {
        // $this->markTestSkipped();
        $this->expectException(InvalidArgumentException::class);
        parseSeat("r\4c76");
    }

    public function testParseSeatGivenCorrectInput()
    {
        // $this->markTestSkipped();
        $expected = array(5,83);
        $actual = parseSeat("R5c83   ");
        $this->assertEquals($actual, $expected);
    }

    ### public function parseIntialString(ir) ###
    public function testInitialStringWithBadInput()
    {
        // $this->markTestSkipped();
        $this->expectException(InvalidArgumentException::class);
        parseIntialString("R2C5\4");
    }

    public function testInitialInputCanBeFlexible()
    {
        // $this->markTestSkipped();
        $expected = [[3,5],[2,7],[1,4]];
        $actual = parseIntialString("R3c5 R2C7    r1C4");
        $this->assertEquals($actual, $expected);
    }

    ### public function parseOutput($res) ###
    public function testParseOutputWithThreeSeatsReserved()
    {
        // $this->markTestSkipped();
        $expected = "R1C7 - R1C9\n";
        $actual = parseOutput([[1,7],[1,9]]);
        $this->assertEquals($actual, $expected);
    }

    public function testParseOutputWithOneSeatReserved()
    {
        // $this->markTestSkipped();
        $expected = "R1C5\n";
        $actual = parseOutput([[1,5],[1,5]]);
        $this->assertEquals($actual, $expected);
    }

    ### public function additional_reservations(chart,n_seats) ###
    public function testRowNotWideEnough()
    {
        // $this->markTestSkipped();
        $chart = new SeatingChart(5,5);
        $expected = null;
        $actual = additionalReservation($chart,10);
        $this->assertEquals($actual, $expected);
    }
}