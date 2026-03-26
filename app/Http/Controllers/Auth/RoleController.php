<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function update()
    {
        $user = auth()->user();
        $user->role = request('role');
        $user->save();

        return redirect()->back()->with('status', 'Role updated successfully!');
    }
}
