<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lpk;
use Illuminate\Support\Facades\Storage;

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
        $lpk = Lpk::where('tenant_id', $user->tenant_id)->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */
        return view('admin.profile', compact('lpk', 'user'));
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
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:25',
            'legal_name' => 'nullable|string|max:255',
            'phone_lpk' => 'nullable|string|max:25',
            'wa_number' => 'nullable|string|max:25',
            'facilities' => 'nullable|array',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,jpg,png|max:5120',
            // VALIDASI DILONGGARKAN AGAR BISA MENERIMA JSON STRING DARI FORM
            'contact_info' => 'nullable|string|max:1000',
            'logo' => 'nullable', // Relaxed to allow both file and Base64 string
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

            // 🔥 FIX BUG: KONVERSI CONTACT INFO MENJADI ARRAY YANG BENAR 🔥
            if ($request->has('contact_info')) {
                $contactInfo = $request->contact_info;

                // Jika input berupa string (termasuk string JSON), kita decode menjadi array
                if (is_string($contactInfo)) {
                    $decoded = json_decode($contactInfo, true);
                    // Jika proses decode sukses dan menghasilkan array, pakai hasil decodenya
                    // Jika gagal (bukan format json), paksa masuk ke dalam array 1 dimensi
                    $contactInfo = (json_last_error() === JSON_ERROR_NONE && is_array($decoded))
                        ? $decoded
                        : [$contactInfo];
                }

                $lpk->contact_info = $contactInfo;
            }

            // Handle Base64 from Cropper or standard file upload
            $base64Input = $request->filled('cropped_logo') ? $request->cropped_logo : (is_string($request->logo) ? $request->logo : null);

            if ($base64Input && strpos($base64Input, 'data:image') === 0) {
                $image_parts = explode(";base64,", $base64Input);
                if (count($image_parts) == 2) {
                    $image_base64 = base64_decode($image_parts[1]);
                    $fileName = 'lpks/' . uniqid() . '.png';
                    Storage::disk('public')->put($fileName, $image_base64);
                    $lpk->logo = $fileName;
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