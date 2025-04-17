-- Create database (if not exists) and use it.
CREATE DATABASE IF NOT EXISTS events;
USE events;

-- ********************
-- Table: cities
-- ********************
DROP TABLE IF EXISTS cities;
CREATE TABLE cities (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255) NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ********************
-- Table: categories
-- ********************
DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255) NOT NULL,
parent_id INT DEFAULT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
CONSTRAINT fk_parent_category
FOREIGN KEY (parent_id) REFERENCES categories(id)
    ON DELETE CASCADE
);

-- ********************
-- Table: events
-- ********************
DROP TABLE IF EXISTS events;
CREATE TABLE events (
id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(255) NOT NULL,
description TEXT DEFAULT NULL,
event_date DATETIME DEFAULT NULL,
city_id INT NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
CONSTRAINT fk_city_event
FOREIGN KEY (city_id) REFERENCES cities(id)
ON DELETE CASCADE
);

-- ********************
-- Table: event_categories (Join table for Many-to-Many)
-- ********************
DROP TABLE IF EXISTS event_categories;
CREATE TABLE event_categories (
  event_id INT NOT NULL,
  category_id INT NOT NULL,
  PRIMARY KEY (event_id, category_id),
  CONSTRAINT fk_event
      FOREIGN KEY (event_id) REFERENCES events(id)
          ON DELETE CASCADE,
  CONSTRAINT fk_category
      FOREIGN KEY (category_id) REFERENCES categories(id)
          ON DELETE CASCADE
);
