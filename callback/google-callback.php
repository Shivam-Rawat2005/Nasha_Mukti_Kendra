<?php
require_once '../config/db.php';
require_once '../config/auth.php';
require_once '../config/google.php';

// Verify state for CSRF protection
if (!isset($_GET['state']) || $_GET['state'] !== $_SESSION['oauth_state']) {
    die('State mismatch. Possible CSRF attack.');
}

// Check for errors
if (isset($_GET['error'])) {
    die('Error: ' . htmlspecialchars($_GET['error']));
}

// Get authorization code
if (!isset($_GET['code'])) {
    die('No authorization code received.');
}

$code = $_GET['code'];

try {
    // Initialize Google OAuth
    $google = initializeGoogleOAuth();
    
    // Verify credentials are set
    if (GOOGLE_CLIENT_ID === 'YOUR_GOOGLE_CLIENT_ID_HERE') {
        die('ERROR: Google OAuth is not configured. Please update GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET in config/google.php');
    }
    
    // Exchange code for access token
    $tokenResponse = $google->getAccessToken($code);
    
    if (!isset($tokenResponse['access_token'])) {
        die('Failed to get access token. Response: ' . print_r($tokenResponse, true));
    }
    
    // Get user info
    $userInfo = $google->getUserInfo($tokenResponse['access_token']);
    
    if (!isset($userInfo['id']) || !isset($userInfo['email'])) {
        die('Failed to get user info from Google.');
    }
    
    $googleId = $userInfo['id'];
    $email = $userInfo['email'];
    $name = $userInfo['name'] ?? explode('@', $email)[0];
    $picture = $userInfo['picture'] ?? null;
    
    // Check if user exists by google_id
    $stmt = $conn->prepare("SELECT * FROM users WHERE google_id = ?");
    $stmt->bind_param("s", $googleId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // User exists, log them in
        $user = $result->fetch_assoc();
        loginUser($user);
        
        // Update profile picture if changed
        if ($picture) {
            $updateStmt = $conn->prepare("UPDATE users SET google_profile_picture = ? WHERE id = ?");
            $updateStmt->bind_param("si", $picture, $user['id']);
            $updateStmt->execute();
            $updateStmt->close();
        }
        
        $stmt->close();
        header('Location: ../index.php');
        exit();
    } else {
        // Check if email exists (link account)
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $emailResult = $stmt->get_result();
        $stmt->close();
        
        if ($emailResult->num_rows > 0) {
            // Email exists, link Google to existing account
            $user = $emailResult->fetch_assoc();
            $stmt = $conn->prepare("UPDATE users SET google_id = ?, google_profile_picture = ? WHERE id = ?");
            $stmt->bind_param("ssi", $googleId, $picture, $user['id']);
            $stmt->execute();
            $stmt->close();
            
            loginUser($user);
            header('Location: ../index.php');
            exit();
        }
        
        // Create new user
        $username = explode('@', $email)[0];
        $counter = 1;
        $originalUsername = $username;
        
        // Ensure unique username
        while ($conn->query("SELECT id FROM users WHERE username = '$username' LIMIT 1")->num_rows > 0) {
            $username = $originalUsername . $counter;
            $counter++;
        }
        
        $stmt = $conn->prepare("INSERT INTO users (username, email, google_id, google_profile_picture, role) VALUES (?, ?, ?, ?, 'client')");
        $stmt->bind_param("ssss", $username, $email, $googleId, $picture);
        
        if ($stmt->execute()) {
            $userId = $stmt->insert_id;
            $stmt->close();
            
            // Log user in
            $user = [
                'id' => $userId,
                'username' => $username,
                'role' => 'client'
            ];
            loginUser($user);
            header('Location: ../index.php');
            exit();
        } else {
            $stmt->close();
            die('Error creating user: ' . $conn->error);
        }
    }
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}
?>
