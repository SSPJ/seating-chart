#!/usr/bin/env python3
# Seamus Johnston, 2018
import re, sys

from seating_chart import SeatingChart

ROWS = 3       # people to your left and right
COLUMNS = 11   # people behind and in front of you

# Output strings grouped for easier extraction in future l18n
INTIAL_INPUT_ERROR = """Inital reservations must be of the form 'R3C5 R2C8 R4C5'."""
GROUP_RESERVATION_INPUT_ERROR = """Additional reservations must be the party number,
entered numerically, one per line."""
PARSE_SEAT_VALUE_ERROR = """Seat must be given as R#C#, eg 'R3C15'"""

def parse_seat(seat):
  """ Return a tuple (row, column) when given string in the form 'R#C#' """
  r,c = re.match(r"\s*[Rr]([0-9]+)[Cc]([0-9]+)\s*",seat).group(1,2)
  return int(r),int(c)

def parse_intial_string(ir):
  """ Converts a string of reservations into a list of tuples.

  "R1C3 R2C5 R3C6" -> [(1,3),(2,5),(3,6)]
  """
  rc_tuples = []
  if re.search(r"[^RCrc0-9\s]+",ir):
    print(INTIAL_INPUT_ERROR)
    sys.exit(1)
  for res in ir.split():
    try:
      rc_tuples.append(parse_seat(res))
    except AttributeError:
      print(PARSE_SEAT_VALUE_ERROR)
      sys.exit(1)
  return rc_tuples

def parse_output(res):
  """ Convert a list of seat tuples into a formated string. """
  if res[0][1] == res[1][1]:
    return "R{0}C{1}".format(res[0][0],res[0][1])
  else:
    return "R{0}C{1} - R{2}C{3}".format(res[0][0],res[0][1],res[1][0],res[1][1])

def additional_reservation(chart,n_seats):
  """ Reserve a group of seats or return None. """
  if n_seats.isdigit():
    return chart.reserve(int(n_seats))
  else:
    print(GROUP_RESERVATION_INPUT_ERROR)
    return None

if __name__ == "__main__":
  ### Create the SeatingChart ojbect ###
  chart = SeatingChart(ROWS,COLUMNS)

  ### Get initial reservations ###
  initial_reservations = input()
  reservation_list = parse_intial_string(initial_reservations)
  if reservation_list:
    chart.initialize(reservation_list)

  ### Obtain further reservations ###
  try:
    while True:
      number_of_seats = input()
      seats_reserved = additional_reservation(chart,number_of_seats)
      if seats_reserved:
        print(parse_output(seats_reserved))
      else:
        print("Not Available")
  except EOFError:
    print(chart.number_available)