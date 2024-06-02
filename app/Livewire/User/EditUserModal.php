<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class EditUserModal extends Component
{
    use WithFileUploads;

    public $user_id;
    public $name;
    public $email;
    public $phone;
    public $role;
    public $avatar;
    public $saved_avatar;
    public $edit_mode = false;

    protected $listeners = ['editUser' => 'loadUser'];

    public function loadUser($userId)
    {
        $user = User::find($userId);

        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->role = $user->roles->first()->name ?? null;
        $this->saved_avatar = $user->profile_photo_path;
        $this->edit_mode = true;
    }

    public function render()
    {
        $roles = Role::all();

        $roles_description = [
            'administrator' => 'Best for business owners and company administrators',
            'developer' => 'Best for developers or people primarily using the API',
            'analyst' => 'Best for people who need full access to analytics data, but don\'t need to update business settings',
            'support' => 'Best for employees who regularly refund payments and respond to disputes',
            'trial' => 'Best for people who need to preview content data, but don\'t need to make any updates',
        ];

        foreach ($roles as $i => $role) {
            $roles[$i]->description = $roles_description[$role->name] ?? '';
        }

        return view('livewire.user.edit-user-modal', compact('roles'));
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->user_id,
            'phone' => 'required|string|max:15',
            'role' => 'required|string|exists:roles,name',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        DB::transaction(function () {
            $user = User::find($this->user_id);

            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
            ];

            if ($this->avatar) {
                $data['profile_photo_path'] = $this->avatar->store('avatars', 'public');
            }

            $user->update($data);

            $user->syncRoles($this->role);

            $this->dispatch('success', __('User updated'));
        });

        $this->reset();
    }
}
