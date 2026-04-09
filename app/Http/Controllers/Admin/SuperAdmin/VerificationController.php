<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Lpk;
use Illuminate\Http\Request;

class VerificationController extends Controller
{

public function index()
{

$lpks = Lpk::latest()->get();

return view(
'super_admin.verifications.index',
compact('lpks')
);

}



public function approve($id)
{

$lpk = Lpk::findOrFail($id);

$lpk->status_verifikasi = 'approved';

$lpk->save();



return back()
->with('success','LPK approved');

}



public function reject($id)
{

$lpk = Lpk::findOrFail($id);

$lpk->status_verifikasi = 'rejected';

$lpk->save();



return back()
->with('success','LPK rejected');

}

}