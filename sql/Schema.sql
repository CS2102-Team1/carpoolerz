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
  numplate	VARCHAR(10),
  driver		VARCHAR(40),
  FOREIGN KEY (driver) REFERENCES car(numplate),
  FOREIGN KEY (driver) REFERENCES systemuser(username),
  from_address		VARCHAR(40),
  to_address		VARCHAR(40),
  start_time	TIMESTAMP NOT NULL,
  end_time		TIMESTAMP DEFAULT NULL -- driver to press end_time button. eliminate all estimated time complications
);

CREATE TABLE bid (
  amount 		NUMERIC CHECK(amount > 0 OR amount IS NULL) DEFAULT NULL,
  ride_id		NUMERIC,
  passenger	VARCHAR(40),
  PRIMARY KEY (ride_id, passenger),
  FOREIGN KEY (passenger) REFERENCES systemuser(username),
  FOREIGN KEY (ride_id) REFERENCES ride(ride_id),
  success BOOLEAN DEFAULT FALSE
);

CREATE TABLE car_used_in_ride (
  ride_id NUMERIC,
  numplate VARCHAR(10),
  FOREIGN KEY (ride_id) REFERENCES ride(ride_id),
  PRIMARY KEY (ride_id, numplate)
);

CREATE TABLE created_rides (
  username VARCHAR(40),
  ride_id NUMERIC,
  FOREIGN KEY (username) REFERENCES systemuser(username),
  FOREIGN KEY (ride_id) REFERENCES ride(ride_id)
);

--**passenger ride "history": select all current bids, successful or not
--** first ride offer, got bid but amount is null. passenger is driver himself.
--**amount is not a primary key, each user can only have 1 bid for 1 ride, if he wants to increase his bid amt, he will update his current bid entry
