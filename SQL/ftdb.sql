CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE exercises (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    activity VARCHAR(50),
    duration INT,
    calories_burned INT,
    date DATE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE diet_plans (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    meal_type VARCHAR(50),
    description TEXT,
    calories INT,
    date DATE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE progress (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    weight DECIMAL(5,2),
    body_fat_percentage DECIMAL(5,2),
    date DATE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
