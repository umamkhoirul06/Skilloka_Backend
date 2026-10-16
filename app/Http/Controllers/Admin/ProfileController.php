<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lpk;
use App\Models\Booking;
use App\Models\Certificate;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PROFILE PAGE (WEB ADMIN)
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
    | UPDATE PROFILE (WEB ADMIN)
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
            
            // Simpan logo hasil crop atau upload file biasa
            $base64Input = $request->filled('cropped_logo')
                ? $request->cropped_logo
                : null;

            if ($base64Input && str_starts_with($base64Input, 'data:image/')) {
                if (!preg_match(
                    '/^data:image\/(jpeg|jpg|png);base64,/',
                    $base64Input,
                    $matches
                )) {
                    return back()
                        ->withErrors(['logo' => 'Format logo tidak valid.'])
                        ->withInput();
                }

                $imageData = substr(
                    $base64Input,
                    strpos($base64Input, ',') + 1
                );

                $imageContents = base64_decode($imageData, true);

                if ($imageContents === false || strlen($imageContents) > 5 * 1024 * 1024) {
                    return back()
                        ->withErrors(['logo' => 'Logo tidak valid atau melebihi 5MB.'])
                        ->withInput();
                }

                $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
                $fileName = 'lpks/' . uniqid('logo_', true) . '.' . $extension;

                if (!Storage::disk('public')->put($fileName, $imageContents)) {
                    return back()
                        ->withErrors(['logo' => 'Logo gagal disimpan.'])
                        ->withInput();
                }

                $lpk->logo = $fileName;
            } elseif ($request->hasFile('logo')) {
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

    /*
    |--------------------------------------------------------------------------
    | API ENDPOINTS (UNTUK FLUTTER MOBILE APP)
    |--------------------------------------------------------------------------
    */

    /**
     * GET /api/profile
     * Mengembalikan data profil user, galeri LPK, dan statistik kursus/sertifikat
     */
    public function apiShow(Request $request)
{
    $user = $request->user();

    $lpk = Lpk::where('tenant_id', $user->tenant_id)->first();

    $activeBookingsCount = Booking::where('user_id', $user->id)
        ->whereIn('status', ['active', 'approved', 'paid'])
        ->count();

    $certificatesCount = Certificate::where('user_id', $user->id)->count();

    $lpkImages = [];

    if ($lpk && !empty($lpk->images)) {
        $rawImages = is_array($lpk->images)
            ? $lpk->images
            : json_decode($lpk->images, true);

        if (is_array($rawImages)) {
            $lpkImages = array_values($rawImages);
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Data profil berhasil diambil',
        'data' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '',
            // Preferensi avatar pengguna
'avatar_type' => $user->avatar_type ?? 'photo',
'avatar_id' => $user->avatar_id,
'frame_id' => $user->frame_id ?? 'none',

            // FOTO PROFIL USER
            // Foto disimpan di kolom users.photo
            'photo_url' => $user->photo
                ? url('storage/' . $user->photo)
                : null,

            'active_bookings_count' => $activeBookingsCount,
            'certificates_count' => $certificatesCount,

            'lpk' => $lpk
                ? [
                    'id' => $lpk->id,
                    'name' => $lpk->name ?? $lpk->legal_name,
                    'logo' => $lpk->logo,
                    'images' => $lpkImages,
                ]
                : null,
        ],
    ], 200);
}
}