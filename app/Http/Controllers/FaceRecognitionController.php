<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FaceRecognitionController extends Controller
{
    /**
     * Show registration form with face capture
     */
    public function showRegister()
    {
        return view('auth.register-facial');
    }

    /**
     * Register user with facial recognition
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'face_image' => 'required|string',
            'face_encoding' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'language_preference' => 'nullable|in:en,sw',
        ]);

        // Save face image
        $faceImage = $request->face_image;
        $faceImage = str_replace('data:image/png;base64,', '', $faceImage);
        $faceImage = str_replace(' ', '+', $faceImage);
        $faceImageName = 'face_' . Str::uuid() . '.png';
        $path = 'faces/' . $faceImageName;
        Storage::disk('public')->put($path, base64_decode($faceImage));

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'face_encoding' => json_decode($request->face_encoding),
            'face_image_path' => $path,
            'points' => 0,
            'level' => 1,
            'total_scans' => 0,
            'total_reports' => 0,
            'ewaste_count' => 0,
            'location' => $request->latitude && $request->longitude 
                ? "{$request->latitude},{$request->longitude}" 
                : null,
            'language_preference' => $request->language_preference ?? 'en',
            'last_login_at' => now(),
        ]);

        // Award welcome bonus
        if (method_exists($user, 'addPoints')) {
            $user->addPoints(50, 'Welcome bonus');
        }

        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful!',
            'redirect' => route('dashboard'),
        ]);
    }

    /**
     * Show login form with face recognition
     */
    public function showLogin()
    {
        return view('auth.login-facial');
    }

    /**
     * Login with facial recognition OR password
     */
    public function login(Request $request)
    {
        // Check if it's a face login or password login
        if ($request->has('face_encoding')) {
            return $this->loginWithFace($request);
        } else {
            return $this->loginWithPassword($request);
        }
    }

    /**
     * Login with password (traditional login)
     */
    private function loginWithPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Update last login
            $user = Auth::user();
            $user->last_login_at = now();
            $user->save();

            // Check if it's an AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Welcome back, ' . $user->name . '!',
                    'redirect' => route('dashboard'),
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'points' => $user->points,
                        'level' => $user->level,
                    ],
                ]);
            }

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Welcome back, ' . $user->name . '!');
        }

        // Failed login
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials do not match our records.',
            ], 401);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Login with facial recognition
     */
    private function loginWithFace(Request $request)
    {
        $request->validate([
            'face_encoding' => 'required|string',
        ]);

        $faceEncoding = json_decode($request->face_encoding);

        // Find user by comparing face encodings
        $users = User::whereNotNull('face_encoding')->get();
        $matchedUser = null;
        $minDistance = PHP_FLOAT_MAX;

        foreach ($users as $user) {
            $storedEncoding = $user->face_encoding;
            if (!$storedEncoding) continue;

            // Calculate Euclidean distance
            $distance = $this->calculateFaceDistance($faceEncoding, $storedEncoding);

            // Threshold for face matching (adjust as needed)
            if ($distance < 0.6 && $distance < $minDistance) {
                $minDistance = $distance;
                $matchedUser = $user;
            }
        }

        if ($matchedUser) {
            Auth::login($matchedUser, true);
            $request->session()->regenerate();

            // Update last login
            $matchedUser->last_login_at = now();
            $matchedUser->save();

            return response()->json([
                'success' => true,
                'message' => 'Welcome back, ' . $matchedUser->name . '!',
                'redirect' => route('dashboard'),
                'user' => [
                    'name' => $matchedUser->name,
                    'email' => $matchedUser->email,
                    'points' => $matchedUser->points,
                    'level' => $matchedUser->level,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Face not recognized. Please try again or use password login.',
        ], 401);
    }

    /**
     * Recognize user from camera feed (for real-time detection)
     */
    public function recognizeUser(Request $request)
    {
        $request->validate([
            'face_encoding' => 'required|string',
        ]);

        $faceEncoding = json_decode($request->face_encoding);
        
        $users = User::whereNotNull('face_encoding')->get();
        $matchedUser = null;
        $minDistance = PHP_FLOAT_MAX;

        foreach ($users as $user) {
            $storedEncoding = $user->face_encoding;
            if (!$storedEncoding) continue;

            $distance = $this->calculateFaceDistance($faceEncoding, $storedEncoding);
            
            if ($distance < 0.6 && $distance < $minDistance) {
                $minDistance = $distance;
                $matchedUser = $user;
            }
        }

        if ($matchedUser) {
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $matchedUser->id,
                    'name' => $matchedUser->name,
                    'email' => $matchedUser->email,
                    'phone' => $matchedUser->phone,
                    'points' => $matchedUser->points,
                    'level' => $matchedUser->level,
                    'total_scans' => $matchedUser->total_scans,
                    'total_reports' => $matchedUser->total_reports,
                    'face_image' => Storage::url($matchedUser->face_image_path),
                ],
                'confidence' => (1 - $minDistance) * 100,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No matching user found',
        ]);
    }

    /**
     * Calculate Euclidean distance between two face encodings
     */
    private function calculateFaceDistance($encoding1, $encoding2)
    {
        if (count($encoding1) !== count($encoding2)) {
            return PHP_FLOAT_MAX;
        }

        $sumSquares = 0;
        for ($i = 0; $i < count($encoding1); $i++) {
            $diff = $encoding1[$i] - $encoding2[$i];
            $sumSquares += $diff * $diff;
        }

        return sqrt($sumSquares);
    }

    /**
     * Update user's face encoding
     */
    public function updateFaceEncoding(Request $request)
    {
        $request->validate([
            'face_image' => 'required|string',
            'face_encoding' => 'required|string',
        ]);

        $user = Auth::user();

        // Delete old face image
        if ($user->face_image_path) {
            Storage::disk('public')->delete($user->face_image_path);
        }

        // Save new face image
        $faceImage = $request->face_image;
        $faceImage = str_replace('data:image/png;base64,', '', $faceImage);
        $faceImage = str_replace(' ', '+', $faceImage);
        $faceImageName = 'face_' . Str::uuid() . '.png';
        $path = 'faces/' . $faceImageName;
        Storage::disk('public')->put($path, base64_decode($faceImage));

        $user->update([
            'face_encoding' => json_decode($request->face_encoding),
            'face_image_path' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Face updated successfully!',
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }
}