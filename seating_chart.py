class SeatingChart:
  def __init__(self,rows,columns):
    self.rows = rows
    self.cols = columns

  def _individual(self,seat):
    pass

  def _find_seats(self,sought):
    # sudo code 
# check the first row
#   if #seats found, record the taxicab distance & $seats
#     check subsequent rows within the taxicab distance for better seats
#     if better found, update $seats and taxicab distance
# if no seats found, check first row + 1
    pass

  def reserve(self,seats):
    if isinstance(seats,str):
      reservations = _individual(seats)
    elif isinstance(seats,int):
      reservations = _find_seats(seats)
    else:
      raise AttributeError("Reservation must be integer or string")
    return reservations

  def reserved(self,seat):
    pass
