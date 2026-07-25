<?php

use App\Models\Membership\Member;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    return redirect()->to('/onboarding');
});


Route::get('/membership/vcard/{id}', function ($id) {
    $member = Member::find($id);
    $organization = $member->organization;

    return view('vcard.vcard', compact('member', 'organization'));
});
