DROP TABLE systemuser;
DROP TABLE car;
DROP TABLE ride;
DROP TABLE user_bid;

CREATE TABLE systemuser (
    username VARCHAR(64) PRIMARY KEY,
    password VARCHAR(64) NOT NULL,
    fullName VARCHAR(64) NOT NULL,
    licenseNum VARCHAR(64) NOT NULL,
    numPlate VARCHAR(64) SET DEFAULT NULL REFERENCES Car(numPlate) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE car (
    numPlate VARCHAR(64) PRIMARY KEY,
    brand VARCHAR(64) NOT NULL,
    model VARCHAR(64) NOT NULL
);

CREATE TABLE ride (
    ride_id NUMERIC AUTO_INCREMENT PRIMARY KEY,
    startpoint VARCHAR(64) NOT NULL,
    endpoint VARCHAR(64) NOT NULL,
    starttime_date TIMESTAMP NOT NULL,
    endtime_date TIMESTAMP NOT NULL
);

CREATE TABLE user_bid (
    bidAmount NUMERIC,
    success BOOLEAN,
    passgsrcdest VARCHAR(64),
    passgfindest VARCHAR(64),
    FOREIGN KEY (username) REFERENCES systemuser (username),
    FOREIGN KEY (ride_id) REFERENCES ride (ride_id),
    PRIMARY KEY (username, ride_id)
);

/*--KIV-- To be decided later
CREATE VIEW user_owns (
   FOREIGN KEY (numPlate) REFERENCES car(numPlate) ON UPDATE CASCADE ON DELETE CASCADE,
   FOREIGN KEY (username) REFERENCES systemuser(username) ON UPDATE CASCADE ON DELETE
);*/
