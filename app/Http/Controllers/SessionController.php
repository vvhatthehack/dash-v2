<?php
// app/Http/Controllers/SessionController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\SessionHistory;

class SessionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $sessions = SessionHistory::where('user_id', $user->id)->orderBy('last_activity', 'desc')->get();
        return $sessions;
    }
    public function store(Request $request)
    {
        $geoip = geoip()->getLocation($request->ip());

        $session = new SessionHistory();
        $session->user_id = Auth::id();
        $session->ip_address = $request->ip();
        $session->user_agent = $request->userAgent();
        $session->last_activity = now();
        $session->is_active = true;
        $session->country = $geoip->country;
        $session->city = $geoip->city;
        $session->region = $geoip->state;
        $session->timezone = $geoip->timezone;
        $session->save();

        return response()->json(['success' => true, 'message' => 'Session created successfully.']);
    }

    public function destroy($id)
    {
        $session = SessionHistory::findOrFail($id);

        // Check if the user is ending their own session
        $isOwnSession = $session->user_id === Auth::id() && $session->id === Session::getId();

        // Invalidate the session in the session handler
        Session::getHandler()->destroy($session->id);

        // Set is_active to false in the database
        $session->is_active = false;
        $session->save();

        // If it's the user's own session, log them out and invalidate the session
        if ($isOwnSession) {
            Auth::logout();
            Session::invalidate();
            Session::regenerateToken();

            return response()->json(['success' => true, 'message' => 'Session ended You are now logged out.', 'logout' => true]);
        }

        return response()->json(['success' => true, 'message' => 'Session ended']);
    }
}
