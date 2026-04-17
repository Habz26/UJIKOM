<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $tab = $request->get('tab', 'info');
        $search = $request->get('search');
        
        $categoriesData = app(\App\Http\Controllers\CategoryController::class)->index($request);
        $categories = $categoriesData->getData()['categories'];
        
        return view('profile.edit', [
            'user' => $request->user(),
            'categories' => $categories,
            'activeTab' => $tab,
        ]);
    }

    /**
     * Display category management (for profile tab).
     */
    public function categories(Request $request)
    {
        $controller = app(\App\Http\Controllers\CategoryController::class);
        $view = $controller->index($request);
        return view('profile.edit', [
            'user' => $request->user(),
            'categories' => $view->getData()['categories'],
            'activeTab' => 'categories',
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
