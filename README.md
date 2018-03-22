### Purpose

This is a toy; it might be useful but is not intended for use.

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

**Code ©2018, Seamus Johnston, all rights reserved except as required by upstream licenses**