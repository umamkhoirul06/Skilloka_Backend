<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Lpk;

class ProfileController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | PROFILE PAGE
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        /*
        |--------------------------------------------------------------------------
        | USER LOGIN
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();



        /*
        |--------------------------------------------------------------------------
        | SAFETY LOGIN
        |--------------------------------------------------------------------------
        */

        if (!$user) {

            abort(403);

        }



        /*
        |--------------------------------------------------------------------------
        | GET LPK BY TENANT
        |--------------------------------------------------------------------------
        */

        $lpk = Lpk::where(

                'tenant_id',
                $user->tenant_id

            )

            ->firstOrFail();



        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(

            'admin.profile',

            compact('lpk')

        );

    }



    /*
    |--------------------------------------------------------------------------
    | UPDATE PROFILE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request)
    {

        /*
        |--------------------------------------------------------------------------
        | USER LOGIN
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();



        /*
        |--------------------------------------------------------------------------
        | SAFETY LOGIN
        |--------------------------------------------------------------------------
        */

        if (!$user) {

            abort(403);

        }



        /*
        |--------------------------------------------------------------------------
        | GET LPK
        |--------------------------------------------------------------------------
        */

        $lpk = Lpk::where(

                'tenant_id',
                $user->tenant_id

            )

            ->firstOrFail();



        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'name' => 'required|max:255',

            'legal_name' => 'nullable|max:255',

            'nib' => 'nullable|max:255',

            'address' => 'nullable',

            'description' => 'nullable',

            'facilities' => 'nullable',

            'contact_info' => 'nullable',

            'lat' => 'nullable',

            'long' => 'nullable',

            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',

        ]);



        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA
        |--------------------------------------------------------------------------
        */

        $lpk->update([

            'name' => $request->name,

            'legal_name' => $request->legal_name,

            'nib' => $request->nib,

            'address' => $request->address,

            'description' => $request->description,

            'facilities' => $request->facilities,

            'contact_info' => $request->contact_info,

            'lat' => $request->lat,

            'long' => $request->long,

        ]);



        /*
        |--------------------------------------------------------------------------
        | UPLOAD LOGO
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            $logo = $request

                ->file('logo')

                ->store(

                    'lpk/logo',

                    'public'

                );



            $lpk->update([

                'logo' => $logo

            ]);

        }



        /*
        |--------------------------------------------------------------------------
        | UPLOAD GALLERY
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('images')) {

            $images = [];



            foreach ($request->file('images') as $image) {

                $images[] = $image->store(

                    'lpk/gallery',

                    'public'

                );

            }



            $lpk->update([

                'images' => json_encode($images)

            ]);

        }



        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return back()->with(

            'success',

            'Profil LPK berhasil diperbarui'

        );

    }

}