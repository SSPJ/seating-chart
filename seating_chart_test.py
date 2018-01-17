import unittest

from seating_chart import SeatingChart

class SeatingChartTests(unittest.TestCase):

  def test_reserve_takes_only_strs_or_ints(self):
    chart = SeatingChart(5,5)
    with self.assertRaises(AttributeError):
      chart.reserve(4.5)
