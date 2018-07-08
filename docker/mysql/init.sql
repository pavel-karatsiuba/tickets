CREATE DATABASE IF NOT EXISTS tickets;

USE tickets;

CREATE TABLE IF NOT EXISTS t_users (
  tu_id int(11) NOT NULL AUTO_INCREMENT,
  tu_username varchar(512) NOT NULL,
  tu_password varchar(32) NOT NULL,
  PRIMARY KEY (tu_id)
);

CREATE TABLE IF NOT EXISTS t_logged_users (
  tlu_id int(11) NOT NULL AUTO_INCREMENT,
  tlu_user_id int(11) NOT NULL,
  tlu_date int NOT NULL,
  tlu_session_id varchar(128) NOT NULL,
  PRIMARY KEY (tlu_id)
);