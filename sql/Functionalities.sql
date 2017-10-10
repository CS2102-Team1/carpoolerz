--user wants to log in as driver with username '$username' and password '$password'. If no results available then he cannot be authenticated. 
SELECT * FROM systemuser WHERE username = '$username' AND password = '$password' AND licensenum IS NOT NULL;

--user wants to log in as passenger with username '$username' and password '$password'. If no results available then he cannot be authenticated. 
SELECT * FROM systemuser WHERE username = '$username' AND password = '$password';

--user wants to log in as admin. If no results available then he cannot be authenticated.
SELECT * FROM systemuser WHERE username = 'admin@admin.com' AND password = 'adminuser';

------------------------------------- PASSENGER USER INTERFACE -------------------------------------

--passenger wants to view all available rides WITH FULL DETAILS from '$startpt' to '$endpt'. Passenger not allowed to bid cheaper than the current maximum bid
SELECT r.ride_id, r.from_address, r.to_address, r.starttime, r.ridedate, r.numplate, driver.fullname, driver.licensenum, c.model, c.brand, max(b.amount) from ride r, systemuser driver, car c, bid b where r.numplate = c.numplate and r.driver = driver.username and b.ride_id = r.ride_id and r.ridedate = '$date' and starttime > '$currtime' and r.from_address = '$frompt' and r.to_address ='$topt' group by r.ride_id;

--passenger to view his bid history including the details of that ride
SELECT r.ride_id, driver.fullname, driver.licensenum, r.numplate, r.from_address, r.to_address, r.starttime, r.endtime, r.ridedate, b.amount from ride r, systemuser driver, bid b where r.driver = driver.username and r.ride_id = b.ride_id and b.passenger = '$username';

--**passenger ride history: select all, successful or not

--passenger creates bid, inserts '$bidamt'. If less than the current maximum bid, ignore.
INSERT INTO bid VALUES ('$bidamt', '$ride_id', '$username');


------------------------------------- DRIVER USER INTERFACE -------------------------------------

--driver views his ride offers history **varies based on design: include his future ride offers? ya
SELECT r.ride_id, r.numplate, r.date, r.from_address, r.to_address, r.starttime, r.endtime, r.date, b.amount, b.passenger from ride r, bid b where r.ride_id = b.ride_id and driver = '$username' order by r.date, r.starttime ASC;

--driver creates new ride offer. **Scrape off minimum bid by driver? No more bid entry
INSERT INTO ride(numplate,driver,date, from_address,to_address,starttime) VALUES ('$numplate', '$username', '$date', '$from', '$to', '$starttime'); --*estimated end date? when ride finish press finish button? ride.endtime default null?

--**assumption: end time specified by driver

------------------------------------- ADMIN USER INTERFACE -------------------------------------
--view all users
SELECT * FROM systemuser;

--view all offers
SELECT * FROM ride group by driver,numplate;

--view all bid
SELECT * FROM bid group by passenger order by amount;

--view all cars
SELECT * FROM car