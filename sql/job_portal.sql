
CREATE DATABASE IF NOT EXISTS job_portal;
USE job_portal;

CREATE TABLE IF NOT EXISTS jobs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255),
  company VARCHAR(255),
  description TEXT,
  additional_info TEXT,
  state VARCHAR(100),
  city VARCHAR(100),
  industry VARCHAR(100),
  role_category VARCHAR(100),
  skills TEXT,
  job_type VARCHAR(100),
  education VARCHAR(100),
  work_mode VARCHAR(100),
  experience VARCHAR(100),
  salary VARCHAR(100),
  job_link TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL
);

INSERT INTO admins (username, password) VALUES ('admin', MD5('admin123'));
