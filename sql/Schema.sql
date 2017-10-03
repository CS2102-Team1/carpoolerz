DROP TABLE user_bid;
DROP TABLE systemuser;
DROP TABLE car;
DROP TABLE ride;
DROP TABLE user_bid;

--Order of Implementation
--1. car table
--2. ride table
--3. systemuser table
--4. user_bid tabke

CREATE TABLE car (
    numPlate VARCHAR(64) PRIMARY KEY,
    brand VARCHAR(64) NOT NULL,
    model VARCHAR(64) NOT NULL
);

CREATE SEQUENCE ride_id;
CREATE TABLE ride (
    ride_id NUMERIC DEFAULT nextval('ride_id') PRIMARY KEY,
    startpoint VARCHAR(64) NOT NULL,
    endpoint VARCHAR(64) NOT NULL,
    starttime_date TIMESTAMP NOT NULL,
    endtime_date TIMESTAMP NOT NULL
);
ALTER SEQUENCE ride_id OWNED BY ride.ride_id;

CREATE TABLE systemuser (
    username VARCHAR(64) PRIMARY KEY,
    password VARCHAR(64) NOT NULL,
    fullName VARCHAR(64) NOT NULL,
    licenseNum VARCHAR(64) NOT NULL,
    numPlate VARCHAR(64) DEFAULT NULL REFERENCES Car(numPlate) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE user_bid (
    bidAmount NUMERIC,
    success BOOLEAN,
    passgsrcdest VARCHAR(64),
    passgfindest VARCHAR(64),
    username VARCHAR(64),
    ride_id NUMERIC(64),
    FOREIGN KEY (username) REFERENCES systemuser (username),
    FOREIGN KEY (ride_id)  REFERENCES ride (ride_id),
    PRIMARY KEY (username, ride_id)
);

/*--KIV-- To be decided later
CREATE VIEW user_owns (
   FOREIGN KEY (numPlate) REFERENCES car(numPlate) ON UPDATE CASCADE ON DELETE CASCADE,
   FOREIGN KEY (username) REFERENCES systemuser(username) ON UPDATE CASCADE ON DELETE
);*/