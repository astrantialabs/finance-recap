<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Illuminate\Http\Request;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Edit extends Component
{
    public $payload_user;
    public $payload_role;
    public $payload_role_synced;

    public function mount($id)
    {
        $user = User::where("username", $id)->first();
        $this->payload_user = $user;
        $this->payload_role_synced = $user->getRoleNames();
        $this->payload_role = Role::all();
    }
    public $superadmin;
    public $sekretariat;
    public $lattas;
    public $hi;
    public $penta;
    public $admin;

    protected $rules = [
        "superadmin" => "required_without_all",
        "sekretariat" => "required_without_all",
        "lattas" => "required_without_all",
        "hi" => "required_without_all",
        "penta" => "required_without_all",
        "admin" => "required_without_all",
    ];
    public function val(Request $request)
    {
        $roles = [$this->superadmin, $this->sekretariat, $this->lattas, $this->hi, $this->penta, $this->admin];
        $user = User::where("username", $this->payload_user->username)->first();

        $user->syncRoles(array_filter($roles, "strlen"));

        $this->validate();
    }
    public function render()
    {
        return view("users.edit", [
            "user" => $this->payload_user,
            "roles" => $this->payload_role,
            "synced_roles" => $this->payload_role_synced,
        ]);
    }
}
