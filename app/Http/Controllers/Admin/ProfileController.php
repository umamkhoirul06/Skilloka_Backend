<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;

class ProfileController extends Controller
{

    public function index()
    {

        // ambil tenant pertama (LPK)
        $tenant = Tenant::orderBy('created_at','asc')->first();

        return view('admin.profile', compact('tenant'));

    }



    public function update(Request $request)
    {

        $tenant = Tenant::orderBy('created_at','asc')->first();

        if(!$tenant){

            return back()->with('error','Tenant tidak ditemukan');

        }


        /*
        BASIC INFO
        */

        $tenant->lpk_name = $request->lpk_name;
        $tenant->legal_name = $request->legal_name;
        $tenant->nib = $request->nib;
        $tenant->description = $request->description;



        /*
        CONTACT
        */

        $tenant->phone = $request->phone;
        $tenant->email = $request->email;
        $tenant->website = $request->website;

        $tenant->instagram = $request->instagram;
        $tenant->facebook = $request->facebook;
        $tenant->tiktok = $request->tiktok;



        /*
        LOCATION
        */

        $tenant->province = $request->province;
        $tenant->city = $request->city;
        $tenant->district = $request->district;

        $tenant->address = $request->address;

        $tenant->latitude = $request->latitude;
        $tenant->longitude = $request->longitude;



        /*
        MEDIA
        */

        if($request->hasFile('logo')){

            $logoPath = $request->file('logo')
                ->store('logo','public');

            $tenant->logo = $logoPath;

        }


        if($request->hasFile('banner')){

            $bannerPath = $request->file('banner')
                ->store('banner','public');

            $tenant->banner = $bannerPath;

        }



        /*
        FACILITIES
        */

        $tenant->facilities = $request->facilities;



        $tenant->save();


        return back()->with('success','Profile berhasil disimpan');

    }

}