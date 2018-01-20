# Seamus Johnston, 2018
import pytest

import driver
from seating_chart import SeatingChart

class TestDriver():

  ### def parse_seat(seat) ###
  def test_parse_seat_given_nonsense(self):
    with pytest.raises(AttributeError):
      driver.parse_seat("r\4c76")

  def test_parse_seat_given_correct_input(self):
    expected = (5,83)
    actual = driver.parse_seat("R5c83   ")
    assert actual == expected

  ### def parse_intial_string(ir) ###
  def test_initial_string_with_bad_input(self):
    with pytest.raises(SystemExit):
      driver.parse_intial_string("R2C5\4")

  def test_initial_input_can_be_flexible(self):
    expected = [(3,5),(2,7),(1,4)]
    actual = driver.parse_intial_string("R3c5 R2C7    r1C4")
    assert actual == expected

  ### def parse_output(res) ###
  def test_parse_output_with_three_seats_reserved(self):
    expected = "R1C7 - R1C9"
    actual = driver.parse_output([(1,7),(1,9)])
    assert actual == expected

  def test_parse_output_with_one_seat_reserved(self):
    expected = "R1C5"
    actual = driver.parse_output([(1,5),(1,5)])
    assert actual == expected

  ### def additional_reservations(chart,n_seats) ###
  def test_row_not_wide_enough(self):
    chart = SeatingChart(5,5)
    expected = None
    actual = driver.additional_reservation(chart,"10")
    assert actual == expected
