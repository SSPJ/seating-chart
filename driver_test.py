import pytest

import driver
from seating_chart import SeatingChart

class TestDriver():

  ### def initialize_reservations(chart, res_lst) ###

  ### def initial_reservation_input() ###
  @pytest.mark.skip(reason="need to implement a generator for the lambda")
  def test_initial_reservation_input_handles_bad_chars(self,monkeypatch):
    monkeypatch.setitem(__builtins__, 'input', lambda: raise EOFError)
    driver.initial_reservation_input()

  def test_intial_reservation_input_parses_known_good_vals(self,monkeypatch):
    monkeypatch.setitem(__builtins__, 'input', lambda: "R3c5 R2C7    r1C4")
    expected = ["R3c5","R2C7","r1C4"]
    actual = driver.initial_reservation_input()
    assert actual == expected

  ### def additional_reservations(chart) ###
  @pytest.mark.skip(reason="need to implement a generator for the lambda")
  def test_row_not_wide_enough(self,monkeypatch,capsys):
    monkeypatch.setitem(__builtins__, 'input', lambda: "10")
    chart = SeatingChart(5,5)
    expected = "Not Available"
    actual = capsys.readouterr().out
    assert actual == expected
