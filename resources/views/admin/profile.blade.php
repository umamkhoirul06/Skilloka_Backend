@extends('layouts.admin')

@section('header', 'LPK Profile Management')

@section('breadcrumbs')
<a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Home</a>
<span class="mx-2">/</span>
<span class="text-gray-800">Profile</span>
@endsection


@section('content')

<div class="bg-white rounded-lg shadow-lg overflow-hidden"
x-data="{ activeTab: 'basic' }">


@if(session('success'))
<div class="m-4 p-3 bg-green-100 text-green-700 rounded">
{{ session('success') }}
</div>
@endif



<!-- TAB MENU -->

<div class="flex border-b border-gray-200">

<button
@click="activeTab = 'basic'"
:class="{ 'border-blue-500 text-blue-600': activeTab === 'basic' }"
class="px-6 py-4 font-medium text-gray-600 hover:text-blue-500 border-b-2 border-transparent">

Basic Info

</button>


<button
@click="activeTab = 'contact'"
:class="{ 'border-blue-500 text-blue-600': activeTab === 'contact' }"
class="px-6 py-4 font-medium text-gray-600 hover:text-blue-500 border-b-2 border-transparent">

Contact & Social

</button>


<button
@click="activeTab = 'location'"
:class="{ 'border-blue-500 text-blue-600': activeTab === 'location' }"
class="px-6 py-4 font-medium text-gray-600 hover:text-blue-500 border-b-2 border-transparent">

Location

</button>


<button
@click="activeTab = 'media'"
:class="{ 'border-blue-500 text-blue-600': activeTab === 'media' }"
class="px-6 py-4 font-medium text-gray-600 hover:text-blue-500 border-b-2 border-transparent">

Media & Facilities

</button>

</div>



<!-- 1 FORM UNTUK SEMUA TAB -->

<form method="POST"
action="{{ route('admin.profile.update') }}"
enctype="multipart/form-data">

@csrf


<div class="p-8">


<!-- BASIC INFO -->

<div x-show="activeTab === 'basic'">

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

<div>

<label class="block text-sm font-medium text-gray-700">
LPK Name
</label>

<input
type="text"
name="lpk_name"
value="{{ old('lpk_name', $tenant->lpk_name ?? '') }}"
class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

</div>



<div>

<label class="block text-sm font-medium text-gray-700">
Legal Name
</label>

<input
type="text"
name="legal_name"
value="{{ old('legal_name', $tenant->legal_name ?? '') }}"
class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

</div>



<div>

<label class="block text-sm font-medium text-gray-700">
NIB
</label>

<input
type="text"
name="nib"
value="{{ old('nib', $tenant->nib ?? '') }}"
class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

</div>



<div class="md:col-span-2">

<label class="block text-sm font-medium text-gray-700">
Description
</label>

<textarea
name="description"
rows="4"
class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

{{ old('description', $tenant->description ?? '') }}

</textarea>

</div>

</div>

</div>




<!-- CONTACT -->

<div x-show="activeTab === 'contact'">

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

<div>

<label class="block text-sm font-medium text-gray-700">
Phone
</label>

<input
type="text"
name="phone"
value="{{ old('phone', $tenant->phone ?? '') }}"
class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

</div>



<div>

<label class="block text-sm font-medium text-gray-700">
Email
</label>

<input
type="text"
name="email"
value="{{ old('email', $tenant->email ?? '') }}"
class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

</div>



<div>

<label class="block text-sm font-medium text-gray-700">
Website
</label>

<input
type="text"
name="website"
value="{{ old('website', $tenant->website ?? '') }}"
class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

</div>



<div>

<label class="block text-sm font-medium text-gray-700">
Instagram
</label>

<input
type="text"
name="instagram"
value="{{ old('instagram', $tenant->instagram ?? '') }}"
class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

</div>



<div>

<label class="block text-sm font-medium text-gray-700">
Facebook
</label>

<input
type="text"
name="facebook"
value="{{ old('facebook', $tenant->facebook ?? '') }}"
class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

</div>



<div>

<label class="block text-sm font-medium text-gray-700">
Tiktok
</label>

<input
type="text"
name="tiktok"
value="{{ old('tiktok', $tenant->tiktok ?? '') }}"
class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

</div>

</div>

</div>




<!-- LOCATION -->

<div x-show="activeTab === 'location'">

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

<div>

<label class="block text-sm font-medium text-gray-700">
Province
</label>

<input
type="text"
name="province"
value="{{ old('province', $tenant->province ?? '') }}"
class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

</div>



<div>

<label class="block text-sm font-medium text-gray-700">
City
</label>

<input
type="text"
name="city"
value="{{ old('city', $tenant->city ?? '') }}"
class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

</div>



<div>

<label class="block text-sm font-medium text-gray-700">
District
</label>

<input
type="text"
name="district"
value="{{ old('district', $tenant->district ?? '') }}"
class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

</div>



<div class="md:col-span-2">

<label class="block text-sm font-medium text-gray-700">
Address
</label>

<textarea
name="address"
rows="3"
class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

{{ old('address', $tenant->address ?? '') }}

</textarea>

</div>



<div>

<label class="block text-sm font-medium text-gray-700">
Latitude
</label>

<input
type="text"
name="latitude"
value="{{ old('latitude', $tenant->latitude ?? '') }}"
class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

</div>



<div>

<label class="block text-sm font-medium text-gray-700">
Longitude
</label>

<input
type="text"
name="longitude"
value="{{ old('longitude', $tenant->longitude ?? '') }}"
class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

</div>

</div>

</div>




<!-- MEDIA -->

<div x-show="activeTab === 'media'">

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

<div>

<label class="block text-sm font-medium text-gray-700">
Logo
</label>

<input
type="file"
name="logo"
class="mt-1 block w-full">

</div>



<div>

<label class="block text-sm font-medium text-gray-700">
Banner
</label>

<input
type="file"
name="banner"
class="mt-1 block w-full">

</div>



<div class="md:col-span-2">

<label class="block text-sm font-medium text-gray-700">
Facilities
</label>

<textarea
name="facilities"
rows="3"
class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

{{ old('facilities', $tenant->facilities ?? '') }}

</textarea>

</div>

</div>

</div>




<!-- SAVE BUTTON -->

<div class="mt-8 flex justify-end">

<button
type="submit"
class="bg-blue-600 text-white px-6 py-2 rounded-md">

Save All Changes

</button>

</div>


</div>

</form>



</div>

@endsection