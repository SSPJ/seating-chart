<?php
// Seamus Johnston, 2018

declare(strict_types=1);

require "SeatingChart.php";

class SeatingChartTest extends PHPUnit\Framework\TestCase
{
    /* class SeatingChart */
    function testCreateNewSeatingChart()
    {
        $chart = new SeatingChart(10,11);
        $this->assertInstanceOf(SeatingChart::class,$chart);
        $this->assertAttributeEquals(10, 'rows', $chart);
        $this->assertAttributeEquals(11, 'cols', $chart);
        $this->assertAttributeEquals(array(), 'reserved_seats', $chart);
        $this->assertAttributeEquals(false, 'sold_out', $chart);
        $this->assertAttributeEquals(0, 'number_reserved', $chart);
        $this->assertAttributeEquals(10 * 11, 'number_available', $chart);
    }

    /* function initialize($res_lst) */
    function testInitializingWithOutOfBoundValues()
    {
        // $this->markTestSkipped();
        $chart = new SeatingChart(5,5);
        $this->expectException(InvalidArgumentException::class);
        $chart->initialize([[1,2],[1,7]]);
    }

    /* function getKey($seat) */
    function testCantChangeHashFormulaByAccident()
    {
        // $this->markTestSkipped();
        $chart = new SeatingChart(10,11);
        $accessGetKey = function($seat) { return $this->getKey($seat); };
        $this->assertEquals($accessGetKey->call($chart, [12,23]), 2300012);
    }

    /* function getTaxi($seat) */
    function testGetTaxiOddCols()
    {
        // $this->markTestSkipped();
        $chart = new SeatingChart(5,9);
        $accessGetTaxi = function($seat) { return $this->getTaxi($seat); };
        $left = $accessGetTaxi->call($chart, [4,8]);
        $right = $accessGetTaxi->call($chart, [4,2]);
        $this->assertEquals($left, $right);
    }

    function testGetTaxiEvenCols()
    {
        // $this->markTestSkipped();
        $chart = new SeatingChart(5,10);
        $accessGetTaxi = function($seat) { return $this->getTaxi($seat); };
        $left = $accessGetTaxi->call($chart, [4,9]);
        $right = $accessGetTaxi->call($chart, [4,2]);
        $this->assertEquals($left, $right);
    }

    /* function individual($seat) */
    function testReservingASeatOnce()
    {
        // $this->markTestSkipped();
        $chart = new SeatingChart(5,5);
        $accessIndividual = function($seat) { return $this->individual($seat); };
        $expected = array([2,5]);
        $actual = $accessIndividual->call($chart, [2,5]);
        $this->assertEquals($actual, $expected);
    }

    function testReservingASeatTwice()
    {
        // $this->markTestSkipped();
        $chart = new SeatingChart(5,5);
        $accessIndividual = function($seat) { return $this->individual($seat); };
        $accessIndividual->call($chart, [2,8]);
        $expected = null;
        $actual = $accessIndividual->call($chart, [2,8]);
        $this->assertEquals($actual, $expected);
    }

    /* function findSeats($sought) */
    function testGroupBiggerThanARow()
    {
        // $this->markTestSkipped();
        $chart = new SeatingChart(10,6);
        $accessFindSeats = function($group) { return $this->findSeats($group); };
        $expected = null;
        $actual = $accessFindSeats->call($chart, 7);
        $this->assertEquals($actual, $expected);
    }

    function testFindBestFourSeatsInEmptyRowOfOddColumns()
    {
        // $this->markTestSkipped();
        $chart = new SeatingChart(1,9);
        $accessFindSeats = function($group) { return $this->findSeats($group); };
        $accessFindSeats->call($chart, 4);
        $expected = array(300001 => 1, 400001 => 1, 500001 => 1, 600001 => 1);
        $this->assertAttributeEquals($expected, 'reserved_seats', $chart);
    }

    function testFindBestFourSeatsInEmptyRowOfEvenColumns()
    {
        // $this->markTestSkipped();
        $chart = new SeatingChart(1,10);
        $accessFindSeats = function($group) { return $this->findSeats($group); };
        $accessFindSeats->call($chart, 4);
        $expected = array(400001 => 1, 500001 => 1, 600001 => 1, 700001 => 1);
        $this->assertAttributeEquals($expected, 'reserved_seats', $chart);
    }

    function testFindBestFiveSeatsInEmptyRowOfOddColumns()
    {
        // $this->markTestSkipped();
        $chart = new SeatingChart(1,9);
        $accessFindSeats = function($group) { return $this->findSeats($group); };
        $accessFindSeats->call($chart, 5);
        $expected = array(300001 => 1, 400001 => 1, 500001 => 1, 600001 => 1, 700001 => 1);
        $this->assertAttributeEquals($expected, 'reserved_seats', $chart);
    }

    function testFindBestFiveSeatsInEmptyRowOfEvenColumns()
    {
        // $this->markTestSkipped();
        $chart = new SeatingChart(1,10);
        $accessFindSeats = function($group) { return $this->findSeats($group); };
        $accessFindSeats->call($chart, 5);
        $expected = array(300001 => 1, 400001 => 1, 500001 => 1, 600001 => 1, 700001 => 1);
        $this->assertAttributeEquals($expected, 'reserved_seats', $chart);
    }

    function testFindBestFiveSeatsInSecondRow()
    {
        // $this->markTestSkipped();
        $chart = new SeatingChart(5,10);
        $chart->initialize([[1,1], [1,2], [1,3], [1,4], [1,5], [1,6], [1,7], [1,8],
                     [1,9], [1,10]]);
        $accessFindSeats = function($group) { return $this->findSeats($group); };
        $accessFindSeats->call($chart, 5);
        $expected = array(400001 => 1, 200001 => 1, 100001 => 1, 900001 => 1, 700001 => 1,
                          600001 => 1, 500001 => 1, 300001 => 1, 1000001 => 1, 800001 => 1,
                          300002 => 1, 400002 => 1, 500002 => 1, 600002 => 1, 700002 => 1);
        $this->assertAttributeEquals($expected, 'reserved_seats', $chart);
    }

    function testNoSpaceLeftForGroup()
    {
        // $this->markTestSkipped();
        $chart = new SeatingChart(2,6);
        $accessFindSeats = function($group) { return $this->findSeats($group); };
        $chart->initialize([[1,1], [1,3], [1,5], [2,2], [2,4], [2,6]]);
        $expected = null;
        $actual = $accessFindSeats->call($chart, 2);
        $this->assertEquals($actual, $expected);
    }

    function testExampleInput()
    {
        // $this->markTestSkipped();
        $chart = new SeatingChart(3,11);
        $chart->initialize([[1,4], [1,6], [2,3], [2,7], [3,9], [3,10]]);
        $accessFindSeats = function($group) { return $this->findSeats($group); };
        $accessFindSeats->call($chart, 3);
        $accessFindSeats->call($chart, 3);
        $accessFindSeats->call($chart, 3);
        $accessFindSeats->call($chart, 1);
        $accessFindSeats->call($chart, 10);
        $expected = array(300002 => 1, 400001 => 1, 400002 => 1, 500001 => 1, 500002 => 1,
                          500003 => 1, 600001 => 1, 600002 => 1, 600003 => 1, 700001 => 1,
                          700002 => 1, 700003 => 1, 800001 => 1, 900001 => 1,
                          900003 => 1, 1000003 => 1);
        $this->assertAttributeEquals($expected, 'reserved_seats', $chart);
    }

    /* function reserve(self,seats) */
    function testReserveTakesOnlyStrsOrInts()
    {
        // $this->markTestSkipped();
        $chart = new SeatingChart(5,5);
        $this->expectException(TypeError::class);
        $chart->reserve(4.5);
    }

    /* function reserved(self,seat) */
    function testReservedSeatIsMarkedReserved()
    {
        // $this->markTestSkipped();
        $chart = new SeatingChart(5,5);
        $chart->reserve([1,3]);
        $expected = true;
        $actual = $chart->reserved([1,3]);
        $this->assertEquals($expected, $actual);
    }

    function testUnreservedSeatIsMarkedUnreserved()
    {
        // $this->markTestSkipped();
        $chart = new SeatingChart(5,5);
        $expected = false;
        $actual = $chart->reserved([1,3]);
        $this->assertEquals($expected, $actual);
    }
}