<?php
/**
 * Google OAuth Configuration
 * Steps to setup:
 * 1. Go to https://console.cloud.google.com/
 * 2. Create a new project
 * 3. Enable Google+ API
 * 4. Create OAuth 2.0 Credentials (Web application)
 * 5. Add Authorized redirect URI: http://localhost/k23bm/Nasha_Mukti_Kendra/callback/google-callback.php
 * 6. Copy Client ID and Client Secret to .env.local (DO NOT COMMIT)
 */

// Load environment variables
require_once __DIR__ . '/env.php';

// ========== CONFIGURATION - LOAD FROM ENVIRONMENT VARIABLES ==========
// Get these from: https://console.cloud.google.com/
// Store in .env.local file (NOT committed to GitHub)
define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID') ?: 'YOUR_GOOGLE_CLIENT_ID_HERE');
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET') ?: 'YOUR_GOOGLE_CLIENT_SECRET_HERE');
define('GOOGLE_CALLBACK_URL', 'http://localhost/k23bm/Nasha_Mukti_Kendra/callback/google-callback.php');
// ========================================================================

class GoogleOAuth {
    private $clientId;
    private $clientSecret;
    private $callbackUrl;
    private $authorizationEndpoint = 'https://accounts.google.com/o/oauth2/v2/auth';
    private $tokenEndpoint = 'https://www.googleapis.com/oauth2/v4/token';
    private $userinfoEndpoint = 'https://www.googleapis.com/oauth2/v1/userinfo';

    public function __construct($clientId, $clientSecret, $callbackUrl) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->callbackUrl = $callbackUrl;
    }

    /**
     * Generate Google OAuth login URL
     */
    public function getLoginUrl($state = null) {
        if (!$state) {
            $state = bin2hex(random_bytes(16));
            $_SESSION['oauth_state'] = $state;
        }

        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->callbackUrl,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state
        ];

        return $this->authorizationEndpoint . '?' . http_build_query($params);
    }

    /**
     * Exchange authorization code for access token
     */
    public function getAccessToken($code) {
        $params = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->callbackUrl,
            'grant_type' => 'authorization_code',
            'code' => $code
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($params)
            ]
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($this->tokenEndpoint, false, $context);
        return json_decode($response, true);
    }

    /**
     * Get user info from Google
     */
    public function getUserInfo($accessToken) {
        $options = [
            'http' => [
                'header' => "Authorization: Bearer {$accessToken}\r\n",
                'method' => 'GET'
            ]
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($this->userinfoEndpoint, false, $context);
        return json_decode($response, true);
    }
}

/**
 * Initialize Google OAuth
 */
function initializeGoogleOAuth() {
    return new GoogleOAuth(
        GOOGLE_CLIENT_ID,
        GOOGLE_CLIENT_SECRET,
        GOOGLE_CALLBACK_URL
    );
}
?>
