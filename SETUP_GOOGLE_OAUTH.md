# Google OAuth Authentication Setup Guide

## Overview
This project now includes Google OAuth 2.0 authentication. Users can log in using their Google account, which automatically creates an account if needed or links to an existing email.

## Feature Description

### What the Google OAuth feature does:
- ✅ Adds a "Login with Google" button on the login page
- ✅ Automatically creates user accounts with Google email
- ✅ Allows existing users to link their Google account
- ✅ Stores Google profile picture (optional)
- ✅ Uses CSRF protection with state tokens
- ✅ Works without requiring manual password entry

---

## Setup Instructions

### Step 1: Get Google OAuth Credentials

1. Go to **Google Cloud Console**: https://console.cloud.google.com/

2. **Create a new project**:
   - Click the project dropdown at the top
   - Click "NEW PROJECT"
   - Enter project name: `Nasha Mukti`
   - Click "CREATE"

3. **Enable Google+ API**:
   - Click "APIs & Services" in the left sidebar
   - Click "ENABLE APIS AND SERVICES"
   - Search for `Google+ API`
   - Click it and press "ENABLE"

4. **Create OAuth Credentials**:
   - Click "OAuth consent screen" in the left sidebar
   - Select "External" user type
   - Click "CREATE"
   - Fill in the form:
     - App name: `Nasha Mukti Kendra`
     - User support email: Your email
     - Developer contact: Your email
   - Click "SAVE AND CONTINUE"
   - Skip optional scopes, click "SAVE AND CONTINUE"
   - Review and click "BACK TO DASHBOARD"

5. **Create OAuth 2.0 Client ID**:
   - Click "Credentials" in the left sidebar
   - Click "+ CREATE CREDENTIALS"
   - Select "OAuth client ID"
   - Choose "Web application"
   - Fill in:
     - Name: `Nasha Mukti Web Client`
     - Authorized JavaScript origins: `http://localhost`
     - Authorized redirect URIs: 
       ```
       http://localhost/k23bm/Nasha_Mukti_Kendra/callback/google-callback.php
       ```
   - Click "CREATE"
   - Copy the **Client ID** and **Client Secret**

### Step 2: Update Configuration File

1. Open `config/google.php` in the Nasha_Mukti_Kendra folder

2. Replace these values (around line 8-10):
   ```php
   define('GOOGLE_CLIENT_ID', 'YOUR_CLIENT_ID_HERE');
   define('GOOGLE_CLIENT_SECRET', 'YOUR_CLIENT_SECRET_HERE');
   ```

   With your actual credentials from Google Cloud Console

3. **Example**:
   ```php
   define('GOOGLE_CLIENT_ID', '1234567890-abc123xyz.apps.googleusercontent.com');
   define('GOOGLE_CLIENT_SECRET', 'GOCSPX-abc123xyz456');
   ```

### Step 3: Update Database (if upgrading)

If you already have the database running, the columns will be added automatically when you load any page:
- `google_id` - Stores Google user ID
- `google_profile_picture` - Stores Google profile image URL

### Step 4: Test the Feature

1. Go to your login page: `http://localhost/k23bm/Nasha_Mukti_Kendra/login.php`

2. You should see a **"Login with Google"** button below the username/password form

3. Click it and follow Google's authentication flow

4. You should be automatically logged in and redirected to the dashboard

---

## How It Works (Technical Overview)

### Files Modified/Created:

1. **config/google.php** - OAuth configuration and GoogleOAuth class
   - Handles OAuth URL generation
   - Token exchange
   - User info retrieval

2. **config/auth.php** - Updated user table schema
   - Added `google_id` and `google_profile_picture` columns
   - Automatically creates columns on first load

3. **callback/google-callback.php** - OAuth callback handler
   - Receives authorization code from Google
   - Exchanges code for access token
   - Retrieves user info
   - Creates or links user account
   - Logs user in

4. **login.php** - Updated login form
   - Added "Login with Google" button
   - Imports Google OAuth class

### User Flow:

```
1. User clicks "Login with Google"
   ↓
2. Redirected to Google's login page
   ↓
3. User authorizes app
   ↓
4. Google redirects to /callback/google-callback.php with authorization code
   ↓
5. Server exchanges code for access token
   ↓
6. Server retrieves user info (email, name, picture, etc.)
   ↓
7. Check if Google ID exists in database:
   - YES → Log user in
   - NO → Check if email exists:
     - YES → Link Google account to existing user
     - NO → Create new user account
   ↓
8. User logged in, redirect to dashboard
```

---

## Deployment (Production)

For live deployment, update the callback URL in:

1. **Google Cloud Console**:
   - Add your domain: `https://yourdomain.com/Nasha_Mukti_Kendra/callback/google-callback.php`

2. **config/google.php**:
   - Update `GOOGLE_CALLBACK_URL` to `https://yourdomain.com/Nasha_Mukti_Kendra/callback/google-callback.php`

3. Ensure your site uses **HTTPS** (required by Google OAuth)

---

## Troubleshooting

### Error: "Google OAuth is not configured"
**Solution**: Update `GOOGLE_CLIENT_ID` and `GOOGLE_CLIENT_SECRET` in `config/google.php`

### Error: "Redirect URI mismatch"
**Solution**: Ensure the redirect URI in Google Cloud Console exactly matches the one in `config/google.php`

### Error: "No authorization code received"
**Solution**: User likely denied permission. No issue, they can try again.

### User not logging in
**Solution**: Check browser console for errors, ensure database has Google columns

### Callback returning blank page
**Solution**: Check PHP error logs, ensure `callback` directory exists and is readable

---

## Security Notes

- ✅ CSRF protection using state tokens
- ✅ Secure token exchange (never exposes tokens in URL)
- ✅ User info retrieved only when needed
- ✅ Passwords not required for Google users
- ✅ Session management handled by existing auth system

---

## Linked Account Feature

If a user's email already exists in the system:
- Their Google account is automatically linked
- Next login via Google automatically logs them in
- No separate registration needed
- Profile picture is updated on each login

---

## Reverting Changes (if needed)

To remove Google OAuth:

1. Delete `/callback/google-callback.php` file
2. Delete `/config/google.php` file
3. Remove Google imports from `login.php` (look for `require_once 'config/google.php'`)
4. Remove Google button from `login.php` HTML
5. Database columns (google_id, google_profile_picture) can stay or be dropped

---

## Questions?

Refer to the inline comments in:
- `config/google.php` - OAuth flow explanation
- `callback/google-callback.php` - User creation/linking logic
- `login.php` - Frontend integration

For official Google OAuth docs: https://developers.google.com/identity/protocols/oauth2
