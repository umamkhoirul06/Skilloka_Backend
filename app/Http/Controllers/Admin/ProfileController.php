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

            compact('lpk', 'user')

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
        | VALIDATION
        |--------------------------------------------------------------------------
        */
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:25',
            'legal_name' => 'nullable|string|max:255',
            'phone_lpk' => 'nullable|string|max:25',
            'wa_number' => 'nullable|string|max:25',
            'facilities' => 'nullable|array',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,jpg,png|max:5120',
            'contact_info' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'cropped_logo' => 'nullable|string'
        ]);

        /*
        |--------------------------------------------------------------------------
        | GET LPK BY TENANT
        |--------------------------------------------------------------------------
        */
        $lpk = Lpk::where('tenant_id', $user->tenant_id)->first();

        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA PENGGUNA
        |--------------------------------------------------------------------------
        */
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        /*
        |--------------------------------------------------------------------------
        | UPLOAD LOGO LPK (CROPPER / FILE) & DATA BARU
        |--------------------------------------------------------------------------
        */
        if ($lpk) {
            // Update additional info
            if ($request->has('legal_name')) {
                $lpk->legal_name = $request->legal_name;
            }
            if ($request->has('phone_lpk')) {
                $lpk->phone = $request->phone_lpk;
            }
            if ($request->has('wa_number')) {
                $lpk->wa_number = $request->wa_number;
            }
            if ($request->has('facilities')) {
                $lpk->facilities = $request->facilities;
            }

            // Upload Gallery Images
            if ($request->hasFile('gallery_images')) {
                $existingImages = is_array($lpk->images) ? $lpk->images : [];
                foreach ($request->file('gallery_images') as $image) {
                    $existingImages[] = $image->store('lpks/gallery', 'public');
                }
                $lpk->images = $existingImages;
            }

            // Update contact info
            if ($request->has('contact_info')) {
                // contact_info is cast to array in Lpk model
                $lpk->contact_info = [$request->contact_info];
            }

            // If Cropper sent a Base64 string
            if ($request->filled('cropped_logo')) {
                $image_parts = explode(";base64,", $request->cropped_logo);
                if (count($image_parts) == 2) {
                    $image_base64 = base64_decode($image_parts[1]);
                    // Validate size (max 5MB)
                    if (strlen($image_base64) <= 5242880) {
                        $fileName = 'lpks/logo_' . time() . '.jpg';
                        \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $image_base64);
                        $lpk->logo = $fileName;
                    } else {
                        return back()->withErrors(['logo' => 'Ukuran gambar crop maksimal 5MB.']);
                    }
                }
            } 
            // Fallback to normal upload
            elseif ($request->hasFile('logo')) {
                $lpk->logo = $request->file('logo')->store('lpks', 'public');
            }
            
            $lpk->save();
        }

        $user->save();

        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */
        return back()->with('success', 'Profil Pengguna dan LPK berhasil diperbarui!');
    }

}