-- Display All Rides For A Specified User (Shows all the rides when user is a driver AND a passenger)
SELECT r.startdate, r.starttime, r.endtime, b.passgsrcdest, b.passgfindest
FROM ride r
INNER JOIN bid b ON r.ride_id = b.ride_id
WHERE b.username = {input_username}

-- Display Rides when User was a driver
SELECT r.startdate, r.starttime, r.endtime, b.passgsrcdest, b.passgfindest
FROM ride r
INNER JOIN bid b ON r.ride_id = b.ride_id
WHERE b.username = {input_username} AND b.amount = 0

-- Display Rides when User was a passenger
SELECT r.startdate, r.starttime, r.endtime, b.passgsrcdest, b.passgfindest
FROM ride r
INNER JOIN bid b ON r.ride_id = b.ride_id
WHERE b.username = {input_username} AND b.amount <> 0
