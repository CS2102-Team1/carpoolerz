INSERT INTO car (numPlate, brand, model) VALUES ('152', 'Toyota', 'Avanza');
INSERT INTO car (numPlate, brand, model) VALUES ('121', 'Honda', 'Jazz');
INSERT INTO car (numPlate, brand, model) VALUES ('341', 'Lamborghini', 'Aventador');

INSERT INTO systemuser (username, password, fullName, licenseNum) VALUES ('user1', 'pass1', 'Derian Tungka', '111');
INSERT INTO systemuser (username, password, fullName, licenseNum) VALUES ('user2', 'pass2', 'Derian Tungka', '222');
INSERT INTO systemuser (username, password, fullName, licenseNum) VALUES ('user3', 'pass3', 'Derian Tungka', '333');

INSERT INTO ride (startpoint, endpoint, starttime_date, endtime_date) VALUES ('NUS UTown', 'Clementi', '2017-06-06 11:11:11', '2017-06-06 12:11:11');
INSERT INTO ride (startpoint, endpoint, starttime_date, endtime_date) VALUES ('NUS School of Computing', 'Jurong East', '2017-06-06 11:11:11', '2017-06-06 12:11:11');
INSERT INTO ride (startpoint, endpoint, starttime_date, endtime_date) VALUES ('NUS Faculty of Engineering', 'Changi Airport', '2017-06-06 11:11:11', '2017-06-06 12:11:11');

INSERT INTO user_bid (bidAmount, success, passgsrcdest, passgfindest, username, ride_id) VALUES (10, TRUE, 'Holland Village', 'Singapore Poly', 'user1', 1);
INSERT INTO user_bid (bidAmount, success, passgsrcdest, passgfindest, username, ride_id) VALUES (20, TRUE, 'Jurong Point', 'Ngee Ann Poly', 'user2', 2);
INSERT INTO user_bid (bidAmount, success, passgsrcdest, passgfindest, username, ride_id) VALUES (30, TRUE, 'Kent Ridge MRT', 'Temasek Poly', 'user3', 3);