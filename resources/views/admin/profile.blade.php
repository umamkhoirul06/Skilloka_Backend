@extends('layouts.admin')

@section('title', 'LPK Profile')

@section('content')

<div
    x-data="{ activeTab: 'basic' }"
    class="max-w-6xl mx-auto p-6 space-y-6">

    <!-- HEADER -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">

        <!-- COVER -->
        <div class="h-36 bg-gradient-to-r from-slate-900 via-blue-800 to-indigo-700"></div>

        <!-- PROFILE -->
        <div class="px-6 pb-6 relative">

            <div class="-mt-12 flex items-end gap-5 flex-wrap">

                <!-- LOGO -->
                <div class="w-24 h-24 rounded-2xl overflow-hidden border-4 border-white shadow-xl bg-white">

                    @if($lpk->logo)

                        <img
                            src="{{ asset('storage/'.$lpk->logo) }}"
                            class="w-full h-full object-cover">

                    @else

                        <div class="w-full h-full bg-slate-100 flex items-center justify-center text-4xl font-bold text-slate-500">

                            {{ strtoupper(substr($lpk->name,0,1)) }}

                        </div>

                    @endif

                </div>

                <!-- INFO -->
                <div class="pb-1">

                    <h1 class="text-3xl font-bold text-slate-800 leading-tight">

                        {{ $lpk->name }}

                    </h1>

                    <div class="flex items-center gap-3 mt-3 flex-wrap">

                        @if($lpk->is_verified)

                            <span class="px-4 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                                Verified
                            </span>

                        @else

                            <span class="px-4 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold">
                                Pending Verification
                            </span>

                        @endif

                        <span class="text-sm text-slate-500">
                            NIB : {{ $lpk->nib ?? '-' }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- MAIN -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">

        <!-- TAB -->
        <div class="flex gap-3 p-4 border-b border-gray-100 bg-slate-50 overflow-auto">

            <!-- BASIC -->
            <button
                @click="activeTab='basic'"
                :class="activeTab==='basic'
                    ? 'bg-blue-600 text-white shadow'
                    : 'text-slate-600 hover:bg-slate-100'"
                class="px-5 py-2.5 rounded-xl text-sm font-semibold transition whitespace-nowrap">

                Basic Info

            </button>

            <!-- LOCATION -->
            <button
                @click="activeTab='location'"
                :class="activeTab==='location'
                    ? 'bg-blue-600 text-white shadow'
                    : 'text-slate-600 hover:bg-slate-100'"
                class="px-5 py-2.5 rounded-xl text-sm font-semibold transition whitespace-nowrap">

                Location

            </button>

            <!-- MEDIA -->
            <button
                @click="activeTab='media'"
                :class="activeTab==='media'
                    ? 'bg-blue-600 text-white shadow'
                    : 'text-slate-600 hover:bg-slate-100'"
                class="px-5 py-2.5 rounded-xl text-sm font-semibold transition whitespace-nowrap">

                Media & Facilities

            </button>

        </div>

        <!-- FORM -->
        <form
            action="{{ route('admin.profile.update') }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            <div class="p-6">

                <!-- BASIC -->
                <div
                    x-show="activeTab==='basic'"
                    x-cloak
                    class="space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- LPK NAME -->
                        <div>

                            <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                LPK Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name',$lpk->name) }}"
                                class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">

                        </div>

                        <!-- LEGAL NAME -->
                        <div>

                            <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                Legal Name
                            </label>

                            <input
                                type="text"
                                name="legal_name"
                                value="{{ old('legal_name',$lpk->legal_name) }}"
                                class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">

                        </div>

                        <!-- NIB -->
                        <div>

                            <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                NIB
                            </label>

                            <input
                                type="text"
                                name="nib"
                                value="{{ old('nib',$lpk->nib) }}"
                                class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">

                        </div>

                        <!-- CONTACT -->
                        <div>

                            <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                Contact Info
                            </label>

                            <input
                                type="text"
                                name="contact_info"
                                value="{{ old('contact_info',$lpk->contact_info) }}"
                                class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">

                        </div>

                    </div>

                    <!-- DESCRIPTION -->
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description',$lpk->description) }}</textarea>

                    </div>

                </div>

                <!-- LOCATION -->
                <div
                    x-show="activeTab==='location'"
                    x-cloak
                    class="space-y-6">

                    <!-- ADDRESS -->
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Full Address
                        </label>

                        <textarea
                            name="address"
                            rows="3"
                            class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address',$lpk->address) }}</textarea>

                    </div>

                    <!-- LAT LONG -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- LAT -->
                        <div>

                            <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                Latitude
                            </label>

                            <input
                                type="text"
                                name="lat"
                                value="{{ old('lat',$lpk->lat) }}"
                                class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">

                        </div>

                        <!-- LONG -->
                        <div>

                            <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                Longitude
                            </label>

                            <input
                                type="text"
                                name="long"
                                value="{{ old('long',$lpk->long) }}"
                                class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">

                        </div>

                    </div>

                </div>

                <!-- MEDIA -->
                <div
                    x-show="activeTab==='media'"
                    x-cloak
                    class="space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- LOGO -->
                        <div>

                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Upload Logo
                            </label>

                            <input
                                type="file"
                                name="logo"
                                class="w-full rounded-xl border border-gray-300 p-3 text-sm">

                        </div>

                        <!-- GALLERY -->
                        <div>

                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Upload Gallery
                            </label>

                            <input
                                type="file"
                                name="images[]"
                                multiple
                                class="w-full rounded-xl border border-gray-300 p-3 text-sm">

                        </div>

                    </div>

                    <!-- FACILITIES -->
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Facilities
                        </label>

                        <textarea
                            name="facilities"
                            rows="4"
                            class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('facilities',$lpk->facilities) }}</textarea>

                    </div>

                </div>

            </div>

            <!-- FOOTER -->
            <div class="px-6 py-4 border-t border-gray-100 bg-slate-50 flex justify-end">

                <button
                    type="submit"
                    class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-md transition">

                    Save Changes

                </button>

            </div>

        </form>

    </div>

</div>

@endsection