<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

use App\Mail\NewUser;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $partners = \App\Partner::orderBy('name', 'asc')->get();
        return view('forms.users', ['partners' => $partners]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User;
        $user->fill($request->except('_token'));
        $user->password = env('DEFAULT_PASSWORD');
        $user->save();

        // Mail::to([$user->email])->cc(['jbatuka@usaid.gov', 'joelkith@gmail.com'])->send(new NewUser($user));
        Mail::to([$user->email])->cc(['joelkith@gmail.com'])->send(new NewUser($user));

        session(['toast_message' => 'User Created.']);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $partners = \App\Partner::orderBy('name', 'asc')->get();
        return view('forms.users', ['partners' => $partners, 'user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->fill($request->except(['_token', 'confirm_password']));
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();
        session(['toast_message' => 'The updates to your profile has been made.']);
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect('/pns/download');
    }

    public function change_password()
    {
        $user = auth()->user();
        session(['session_partner' => $user->partner]);        
        return view('forms.password_update', ['no_header' => true, 'user' => $user]);
    }
}
