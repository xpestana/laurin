<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use Inertia\Inertia;
use Redirect;

class AdminController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_driver()
    {
        return Inertia::render('Profile/Components/CreateDrive');
    }
    public function store_driver(Request $request)
    {
        $validator = $this->validate($request, [
            'firstname'         => 'required|string',
            'lastname'          => 'required|string',
            'phone'            => 'required|string',
            'birthdate'            => 'required|string',
            'email'             => 'required|string|email|max:255|unique:users',
        ]);
        $id = mt_Rand(1000000, 9999999);
        $password = Str::lower(Str::random(8));
        
        $user = User::create([
                'name' => $request->firstname." ".$request->lastname,
                'email' => $request->email,
                'password' => Hash::make($password),
            ]);
        
        $userProfile = $user->profile()->create([
                'firstname'  => $request->firstname,
                'lastname'   => $request->lastname,
                'phone'     => $request->phone,
                'birthdate'     => $request->birthdate,
            ]);

        $user->assignRole('Driver');
       // Mail::to($user->email)->send(new WelcomeReceived($user, $password));
            return Redirect::route('conducteurs')->with(['id'=>$id, 'message' => 'Enregistré avec succès', 'code' => 200, 'status' => 'success']);  
    }
    public function delete_driver(Request $request)
    {
        $user = User::find($request->id);
        $user->active = 0;
        $user->email = $user->email."0";
        $user->save();

        return back()->with(['id'=>$user->id, 'message' => 'Enregistré avec succès', 'code' => 200, 'status' => 'success']);  
    }
    public function edit_driver($id)
    {
        $user = User::find($id)->load('profile');
        return Inertia::render('Profile/Components/UpdateDrive', compact('user'));
    }
    public function update_driver(Request $request, $id)
    {
        $user = User::find($id);
        $user->email = $request->email;
        $user->name = $request->firstname." ".$request->lastname;
        $user->save();

        $profile=Profile::find($user->profile->id);
        $profile->firstname = $request->firstname;
        $profile->lastname = $request->lastname;
        $profile->phone = $request->phone;
        $profile->birthdate = $request->birthdate;
        $profile->save();

        return Redirect::route('conducteurs')->with(['id'=>$user->id, 'message' => 'Enregistré avec succès', 'code' => 200, 'status' => 'success']);  
    }

}
