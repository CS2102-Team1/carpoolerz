CREATE TABLE systemuser (
username	VARCHAR(40) PRIMARY KEY,
fullname	VARCHAR(40) NOT NULL,
password 	VARCHAR(10) NOT NULL,
licensenum 	VARCHAR(10)
);

CREATE TABLE car (
numplate	VARCHAR(10) PRIMARY KEY,
model		VARCHAR(20) NOT NULL,
brand		VARCHAR(20)	NOT NULL
);

CREATE SEQUENCE ride_id;
CREATE TABLE ride (
ride_id		NUMERIC DEFAULT nextval('ride_id') PRIMARY KEY,
numplate	VARCHAR(10) REFERENCES car(numplate),
driver		VARCHAR(40) REFERENCES systemuser(username),
ridedate	DATE,
from_point  VARCHAR(40),
to_point  VARCHAR(40),
starttime	TIMESTAMP NOT NULL, --KIV
endtime		TIMESTAMP DEFAULT NULL --KIV
);

CREATE TABLE bid (
amount 		NUMERIC CHECK (amount > 0),
ride_id		NUMERIC REFERENCES ride(ride_id),
passenger	VARCHAR(40) REFERENCES systemuser(username),
PRIMARY KEY (amount, ride_id, passenger),
success BOOLEAN DEFAULT FALSE
);

--**passenger ride history: filter by success, date <= currdate, time <= currtime
