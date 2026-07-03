CREATE DATABASE IF NOT EXISTS packages;
USE packages;

CREATE TABLE parcels (
                         id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                         tracking_no	VARCHAR(64) UNIQUE NOT NULL ,
                         recipient_name	VARCHAR(100) NOT NULL,
                         address	VARCHAR(255) NOT NULL DEFAULT '',
                         weight	DECIMAL(8,2) NOT NULL,
                         status	VARCHAR(20) NOT NULL DEFAULT 'pending',
                         created_at	TIMESTAMP,
                         updated_at	TIMESTAMP
);


INSERT INTO parcels (tracking_no, recipient_name, address, weight)
VALUES
    ('123456', 'Susan', '123 Maple Street', 1.50),
    ('123457', 'Bob', '4321 Good Street', 4.25),
    ('123458', 'Charlie', '789 Pine Road', 0.75);

SELECT * FROM parcels
WHERE status = 'pending';

UPDATE parcels
SET status = 'delivered'
WHERE id = 1;

DELETE FROM parcels WHERE id = 2;

