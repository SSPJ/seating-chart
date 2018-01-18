#!/usr/bin/env python3
import re, sys

from seating_chart import SeatingChart

ROWS = 5      # people to your left and right
COLUMNS = 6   # people behind and in front of you

# Output strings grouped for easier extraction in future l18n
INTIAL_INPUT_ERROR = """Inital reservations must be of the form 'R3C5 R2C8 R4C5'
Enter them correctly or press Ctrl-D to abort."""
GROUP_RESERVATION_INPUT_ERROR = """Additional reservations must be the party number,
entered numerically, one per line."""

def initialize_reservations(chart, res_lst):
  for res in res_lst:
    chart.reserve(res)

def initial_reservation_input():
  ir = input()
  # If input contains invalid chars, enter an input loop
  # NB: this won't stop silly input like 'RRCC 1216'
  while re.search(r"[^RCrc0-9\s]+",ir):
    print(INTIAL_INPUT_ERROR)
    ir = input()
  return ir.split()

def additional_reservations(chart): 
  while True:
    number_of_seats = input()
    if number_of_seats.isdigit():
      print("Not Available")
      seats_reserved = chart.reserve(int(number_of_seats))
      if seats_reserved:
        # TODO: print seats
        pass
    else:
      print(GROUP_RESERVATION_INPUT_ERROR)

if __name__ == "__main__":
  ### Create the SeatingChart ojbect ###
  chart = SeatingChart(ROWS,COLUMNS)

  ### Get initial reservations ###
  try:
    reservation_list = initial_reservation_input()
  except EOFError:
    # NB: if the SeatingChart object is presisted outside of memory,
    # eg. written to a database, it must be deleted here
    sys.exit()

  if reservation_list:
    initialize_reservations(chart,reservation_list)

  ### Obtain further reservations ###
  try:
    additional_reservations(chart)
  except EOFError:
    pass