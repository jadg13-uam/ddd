<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'cif' => ['required', 'string', 'max:16', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        /*
        $user = User::create([
            'name' => $request->name,
            'cif' => $request->cif,
            'email' => $request->email,
            'cellphone' => $request->cellphone,
            'sex' => $request->sex,
            'postgraduate' => $request->postgraduate,
            'role' => $request->role,
            'major' => $request->major,
            'password' => Hash::make($request->password),
        ]);
        */

        
        $user = new User();
        $user->name = $request->name;
        $user->cif = $request->cif;
        $user->email = $request->email;
        $user->cellphone = $request->cellphone;
        $user->sex = $request->sex;

        $user->major = $request->major;
        $user->password = Hash::make($request->password);
        $user->save();

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
