import re

class SeatingChart:

  _RESERVE_DATA_TYPE_ERROR = """"Reservation must be integer or string"""

  def __init__(self,rows,columns):
    self._rows = rows
    self._cols = columns
    self._reserved_seats = {}
    self._sold_out = False

  def initialize(self, res_lst):
    for res in res_lst:
      self._individual(res)

  def _get_key(self,r,c):
    """ Hash function. """
    return c * 1000 + r

  # def _parse_key(self,key):
  #   """ Undo hash function. """
  #   c = int(key / 1000)
  #   r = key - ( c * 1000 )
  #   return r,c

  def _get_taxi(self,seat):
    """ Return the Manhattan Distance from stage center of a given seat. """
    r,c = seat
    middle = self._cols / 2 + .5 if self._cols % 2 == 0 else self._cols / 2 + 1
    taxi = int(round(abs(c - middle) + abs(r - 1)))
    return taxi

  def _individual(self,seat):
    """ Reserve seat in chart if not already reserved. """
    key = self._get_key(*seat)
    if key not in self._reserved_seats:
      self._reserved_seats[key] = 1
      return [seat]
    else:
      return None

  def _find_seats(self,sought):

    if self._cols < sought or self._sold_out:
      return None

    '''Traverse each row, stage to rear. For each row,
    seek all blocks of unreserved consecutive seats == number sought'''
    candidates = {}
    for row in range(1,self._rows+1):
      seats_to_reserve = []
      for col in range(1,self._cols+1):
        seat = (row,col)
        # Do not take someone else's seat
        if self.reserved(seat):
          seats_to_reserve = []
        else:
          seats_to_reserve.append(seat)
        # Have we found enough seats?
        if len(seats_to_reserve) == sought:
          # Measure how far from stage center
          taxi_left = self._get_taxi(seats_to_reserve[0])
          taxi_right = self._get_taxi(seats_to_reserve[-1])
          taxi_dist = taxi_right if taxi_right >= taxi_left else taxi_left
          # Record these seats for later
          if taxi_dist not in candidates:
            candidates[taxi_dist] = list(seats_to_reserve)
          # Make len(seats_to_reserve) != sought
          del seats_to_reserve[0]

    if candidates:
      reservation_list = candidates[min(candidates,key=int)]
      for res in reservation_list:
        # NB: if the SeatingChart object is presisted to a database,
        # these reservations must be inside a transaction
        self._individual(res)
      return (reservation_list[0],reservation_list[-1])
    else:
      return None

  def reserve(self,seats):
    """ Reserve one seat or attempt to find reservations for a given number.

    Returns None if reservation fails otherwise
    Returns the reserved seats as a sorted list
    """ 
    if isinstance(seats,tuple):
      reservations = self._individual(seats)
    elif isinstance(seats,int):
      reservations = self._find_seats(seats)
    else:
      raise ValueError(self._RESERVE_DATA_TYPE_ERROR)
    return reservations

  def reserved(self,seat):
    """ Is seat resevered? """
    key = self._get_key(*seat)
    return True if key in self._reserved_seats else False