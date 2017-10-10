DROP TABLE user_bid;
DROP TABLE ride;
DROP TABLE systemuser;
DROP TABLE car;

CREATE TABLE car (
    numplate VARCHAR(64) PRIMARY KEY,
    brand VARCHAR(64) NOT NULL,
    model VARCHAR(64) NOT NULL
);

CREATE TABLE systemuser (
    username VARCHAR(64) PRIMARY KEY,
    password VARCHAR(64) NOT NULL,
    fullname VARCHAR(64) NOT NULL,
    licensenum VARCHAR(64) NOT NULL,
    numplate VARCHAR(64) DEFAULT NULL REFERENCES Car(numplate) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE SEQUENCE ride_id;
CREATE TABLE ride (
    ride_id NUMERIC DEFAULT nextval('ride_id') PRIMARY KEY,
    startpoint VARCHAR(64) NOT NULL,
    endpoint VARCHAR(64) NOT NULL,
    starttime TIME NOT NULL,
    endtime TIME NOT NULL
);
ALTER SEQUENCE ride_id OWNED BY ride.ride_id;

CREATE TABLE user_bid (
    bidamount NUMERIC,
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
