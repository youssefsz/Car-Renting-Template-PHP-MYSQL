-- Cental Car Rental Database Schema
-- Run this SQL file to create the database and tables

CREATE DATABASE IF NOT EXISTS cental_db;
USE cental_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cars table
CREATE TABLE IF NOT EXISTS cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    image VARCHAR(255) NOT NULL,
    price_per_day DECIMAL(10, 2) NOT NULL,
    seats INT NOT NULL DEFAULT 4,
    transmission ENUM('AUTO', 'MANUAL', 'AT/MT') DEFAULT 'AUTO',
    fuel_type ENUM('Petrol', 'Diesel', 'Electric', 'Hybrid') DEFAULT 'Petrol',
    year INT NOT NULL,
    mileage VARCHAR(20),
    description TEXT,
    status ENUM('available', 'rented', 'maintenance') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    pickup_location VARCHAR(255) NOT NULL,
    dropoff_location VARCHAR(255) NOT NULL,
    pickup_date DATE NOT NULL,
    pickup_time TIME NOT NULL,
    dropoff_date DATE NOT NULL,
    dropoff_time TIME NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
);

-- Insert default admin user (password: admin123)
INSERT INTO users (name, email, password, phone, role) VALUES 
('Admin', 'admin@cental.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1234567890', 'admin');

-- Insert sample cars
INSERT INTO cars (name, image, price_per_day, seats, transmission, fuel_type, year, mileage, description, status) VALUES
('Mercedes Benz R3', 'img/car-1.png', 99.00, 4, 'AUTO', 'Petrol', 2015, '27K', 'Luxury sedan with premium features and comfortable interior.', 'available'),
('Toyota Corolla Cross', 'img/car-2.png', 128.00, 4, 'AT/MT', 'Petrol', 2015, '27K', 'Reliable crossover SUV perfect for family trips.', 'available'),
('Tesla Model S Plaid', 'img/car-3.png', 170.00, 4, 'AUTO', 'Electric', 2015, '27K', 'High-performance electric vehicle with cutting-edge technology.', 'available'),
('Hyundai Kona Electric', 'img/car-4.png', 187.00, 4, 'AUTO', 'Electric', 2015, '27K', 'Eco-friendly electric SUV with excellent range.', 'available');

