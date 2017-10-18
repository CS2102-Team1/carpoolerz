CREATE TABLE systemuser (
  username	VARCHAR(40) PRIMARY KEY,
  fullname	VARCHAR(40) NOT NULL,
  password 	VARCHAR(10) NOT NULL,
  licensenum 	VARCHAR(10) DEFAULT NULL,
  is_admin BOOLEAN DEFAULT FALSE
);

CREATE TABLE car(
  numplate VARCHAR(10),
  model VARCHAR(20) NOT NULL,
  brand VARCHAR(20) NOT NULL,
  PRIMARY KEY (numplate)
);

CREATE TABLE owns_car (
  driver VARCHAR(40),
  numplate VARCHAR(10),
  FOREIGN KEY (driver) REFERENCES systemuser(username) ON DELETE CASCADE,
  FOREIGN KEY (numplate) REFERENCES car(numplate) ON DELETE CASCADE,
  PRIMARY KEY (numplate, driver)
);

-- Ride history table
CREATE SEQUENCE ride_id;
CREATE TABLE ride (
  ride_id		NUMERIC DEFAULT nextval('ride_id') PRIMARY KEY,
  highest_bid NUMERIC DEFAULT '0',
  driver VARCHAR(40) NOT NULL,
  passenger VARCHAR(40) NOT NULL,
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
  FOREIGN KEY (passenger) REFERENCES systemuser(username) ON DELETE CASCADE,
  FOREIGN KEY (ride_id) REFERENCES ride(ride_id) ON DELETE CASCADE,
  success BOOLEAN DEFAULT FALSE
);

CREATE TABLE created_rides (
  driver VARCHAR(40),
  ride_id NUMERIC,
  FOREIGN KEY (driver) REFERENCES systemuser(username) ON DELETE CASCADE,
  FOREIGN KEY (ride_id) REFERENCES ride(ride_id) ON DELETE CASCADE,
  PRIMARY KEY (driver, ride_id)
);

--**passenger ride "history": select all current bids, successful or not
--** first ride offer, got bid but amount is null. passenger is driver himself.
--**amount is not a primary key, each user can only have 1 bid for 1 ride, if he wants to increase his bid amt, he will update his current bid entry
