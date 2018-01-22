### Purpose

Given an (optional) initial string of reservations and groups of various sizes thereafter, keep track of a venue's seating chart. Given the size of a group, find optimal (distance from stage center) seats for the group and reserve those seats.

### Dependencies

* Python 3+ or Python 2.7+
* pytest 3.0.6
  * https://docs.pytest.org/en/latest/getting-started.html
* PHP 7+
* PHPUnit 6.5.5
  * https://phpunit.de/getting-started-with-phpunit.html

Other versions of these may work but have not been tested.

### Usage

**Python - interactive**
```
$ cd interview_puzzle_Johnston/
$ ./driver.py 
```
Example session:
```
R1C7 R2C6 R1C9 R3C2 R2C5 R1C10    
2
R1C5 - R1C6
6
R3C3 - R3C8
4
R1C1 - R1C4
10
Not Available
<Ctrl-D>
15
```
or more simply as
```
$ ./driver.py < test_data
```

**Python - run tests**
```
$ cd interview_puzzle_Johnston/
$ pytest
```
**PHP - interactive**
```
$ cd interview_puzzle_Johnston/
$ php driver.php
```
**PHP - run tests**
```
$ cd interview_puzzle_Johnston/
$ phpunit SeatingChartTest.php
$ phpunit DriverTest.php
```
### Files

/  
|-- seating_chart.py  
|-- seating_chart_test.py  
|-- driver.py  
|-- driver_test.py  
|  
|-- SeatingChart.php  
|-- SeatingChartTest.php  
|-- driver.php  
|-- driver-helpers.php  
|-- DriverTest.php  
|  
|-- test_data

### Implementation Thoughts

The seating chart stores 1 to indicate a reservation. A separate reservation object with details like name, date, etc, would be ideal, but was outside the scope of the assignment.

Visiting every seat to find the n consecutive best, at a cost of O(k) where k = number of seats, is not the most performant algorithm. It could be sped up, for example, by tracking capacity on a per-row basis, reducing checks on those rows to O(1), or by, once a candidate group of seats are found, searching only for better seats.

Considering the problem domain (largest venues in the world are < 300,000 seats), this algorithm is unlikely to be the bottleneck. Further optimization makes the code less maintainable and should wait until demonstrated need.

An easily foreseeable enhancement is allowing users to select their choice of seats. This algorithm needs a few minor alterations to return a list of every possible location where a group of x could sit together.