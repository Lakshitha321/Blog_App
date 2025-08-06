<?php
// functions.php
session_start();

// Database configuration - XAMPP defaults
$servername = "localhost";
$username = "root";          // XAMPP default MySQL username
$password = "";              // XAMPP default MySQL password (empty)
$dbname = "blog_app";

// Create connection
function getConnection($selectDB = true) {
    global $servername, $username, $password, $dbname;
    
    if ($selectDB) {
        $conn = new mysqli($servername, $username, $password, $dbname);
    } else {
        $conn = new mysqli($servername, $username, $password);
    }
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Initialize database and tables
function initDatabase() {
    global $dbname;
    
    // First connect without selecting database
    $conn = getConnection(false);
    
    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql) === TRUE) {
        // Database created successfully
    } else {
        die("Error creating database: " . $conn->error);
    }
    
    // Now select the database
    $conn->select_db($dbname);
    
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(30) NOT NULL,
        email VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($sql);
    
    // Create posts table
    $sql = "CREATE TABLE IF NOT EXISTS posts (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        author_id INT(6) UNSIGNED,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $conn->query($sql);
    
    $conn->close();
}

// User registration
function registerUser($username, $email, $password) {
    $conn = getConnection();
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        $conn->close();
        return "Email already exists";
    }
    
    // Hash password and insert user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "success";
    } else {
        $stmt->close();
        $conn->close();
        return "Registration failed";
    }
}

// User login
function loginUser($email, $password) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $email;
            $stmt->close();
            $conn->close();
            return "success";
        }
    }
    
    $stmt->close();
    $conn->close();
    return "Invalid credentials";
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Get all blog posts
function getAllPosts() {
    $conn = getConnection();
    
    $sql = "SELECT p.id, p.title, p.content, p.created_at, u.username 
            FROM posts p 
            JOIN users u ON p.author_id = u.id 
            ORDER BY p.created_at DESC";
    
    $result = $conn->query($sql);
    $posts = [];
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
    }
    
    $conn->close();
    return $posts;
}

// Create a new post
function createPost($title, $content, $author_id) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("INSERT INTO posts (title, content, author_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $content, $author_id);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "success";
    } else {
        $stmt->close();
        $conn->close();
        return "Failed to create post";
    }
}

// Get user's posts
function getUserPosts($user_id) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("SELECT id, title, content, created_at FROM posts WHERE author_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $posts = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    return $posts;
}

// Initialize database on first load
initDatabase();
?>