<?php

namespace App\Http\Controllers\Apps;

use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\SessionController;
use Stevebauman\Location\Facades\Location;


class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UsersDataTable $dataTable)
    {
        addJavascriptFile('assets/js/custom/apps/user-management/users/list/add.js');
        addJavascriptFile('assets/js/custom/apps/user-management/users/list/edit.js');
        return $dataTable->render('pages/apps.user-management.users.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('pages/apps.user-management.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'avatar' => 'nullable|image|max:1024',
            'role' => 'required|string|max:255',
        ]);

        try {
            $avatarPath = $request->file('avatar') ? $request->file('avatar')->store('avatars', 'public') : null;

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'whatsapp' => $request->phone,
                'avatar' => $avatarPath,
                'password' => Hash::make('password'), // You can use a random password generator here
            ]);

            $user->assignRole($request->role);

            return response()->json(['success' => true, 'message' => 'User created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while creating the user.']);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $last_login = $user->last_login_at;
        $created_at = $user->created_at;

        if ($last_login) {
            $last_login = Carbon::parse($last_login)->format('d M Y H:i');
        } else {
            $last_login = 'N/A'; // Handle the case where last_login_at is null
        }
        if ($created_at) {
            $created_at = Carbon::parse($created_at)->format('d M Y H:i');
        } else {
            $created_at = 'N/A'; // Handle the case where created_at is null
        }

        // Fetch the user's address
        $address = $user->default_address ?? (object) [
            'address_line_1' => '',
            'address_line_2' => '',
            'city' => '',
            'state' => '',
            'postal_code' => '',
            'country' => ''
        ];

        // Fetch the user's session
        $sessionController = new SessionController();
        $sessions = $sessionController->index()->map(function ($session) {
            $session->country = $this->isLocalIP($session->ip_address) ? 'Local IP' : $session->country;
            return $session;
        });

        // Get GeoIP information using Cloudflare driver
        $ip = request()->ip();
        if ($this->isLocalIP($ip)) {
            $country = 'Local IP';
            $city = 'Local IP';
        } else {
            $geoip = Location::get($ip);

            if ($geoip) {
                $country = $geoip->countryName ?? 'Unknown';
                $city = $geoip->cityName ?? 'Unknown';
            } else {
                $country = 'Unknown';
                $city = 'Unknown';
            }

            // Debugging: Log the geoip information
            \Log::info('GeoIP Information:', ['geoip' => $geoip]);
        }

        return view('pages.apps.user-management.users.show', compact('user', 'last_login', 'address', 'created_at', 'sessions', 'country', 'city'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('user-management.edit', compact('user'));    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    private function isLocalIP($ip)
    {
        return in_array($ip, ['127.0.0.1', '::1']) || substr($ip, 0, 8) === '192.168.' || substr($ip, 0, 7) === '10.0.0';
    }

}
