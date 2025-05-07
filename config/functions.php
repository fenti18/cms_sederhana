<?php
// Function to sanitize input
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to get user data
function getUserData($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

// Function to get all posts
function getAllPosts($pdo) {
    $stmt = $pdo->query("SELECT posts.*, categories.name as category_name, users.username 
                         FROM posts 
                         LEFT JOIN categories ON posts.category_id = categories.id 
                         LEFT JOIN users ON posts.user_id = users.id 
                         ORDER BY posts.created_at DESC");
    return $stmt->fetchAll();
}

// Function to get all categories
function getAllCategories($pdo) {
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    return $stmt->fetchAll();
}

// Function to get all users
function getAllUsers($pdo) {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY username ASC");
    return $stmt->fetchAll();
}
?> 