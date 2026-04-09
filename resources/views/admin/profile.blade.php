@extends('layouts.admin')

@section('header', 'LPK Profile Management')
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Home</a>
    <span class="mx-2">/</span>
    <span class="text-gray-800">Profile</span>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-lg overflow-hidden" x-data="{ activeTab: 'basic' }">
        <!-- Tabs -->
        <div class="flex border-b border-gray-200">
            <button @click="activeTab = 'basic'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'basic' }"
                class="px-6 py-4 font-medium text-gray-600 hover:text-blue-500 border-b-2 border-transparent focus:outline-none transition-colors">
                Basic Info
            </button>
            <button @click="activeTab = 'contact'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'contact' }"
                class="px-6 py-4 font-medium text-gray-600 hover:text-blue-500 border-b-2 border-transparent focus:outline-none transition-colors">
                Contact & Social
            </button>
            <button @click="activeTab = 'location'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'location' }"
                class="px-6 py-4 font-medium text-gray-600 hover:text-blue-500 border-b-2 border-transparent focus:outline-none transition-colors">
                Location
            </button>
            <button @click="activeTab = 'media'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'media' }"
                class="px-6 py-4 font-medium text-gray-600 hover:text-blue-500 border-b-2 border-transparent focus:outline-none transition-colors">
                Media & Facilities
            </button>
        </div>

        <!-- Content -->
        <div class="p-8">
            <!-- Basic Info -->
            <div x-show="activeTab === 'basic'">
                <form>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">LPK Name</label>
                            <input type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                placeholder="Skilloka Training Center">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Legal Name</label>
                            <input type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                placeholder="PT Skilloka Indonesia">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NIB (Nomor Induk Berusaha)</label>
                            <input type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                placeholder="1234567890">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Save
                            Changes</button>
                    </div>
                </form>
            </div>

            <!-- Contact -->
            <div x-show="activeTab === 'contact'" style="display: none;">
                <form>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone / WhatsApp</label>
                            <input type="tel"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Website</label>
                            <input type="url"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Instagram</label>
                            <input type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                prefix="@">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Save
                            Changes</button>
                    </div>
                </form>
            </div>

            <!-- Location -->
            <div x-show="activeTab === 'location'" style="display: none;">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Address Details</label>
                    <textarea rows="2"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        placeholder="Jalan Raya No. 123..."></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Map Location (Click too set)</label>
                    <div
                        class="h-64 bg-gray-200 rounded-md flex items-center justify-center text-gray-500 border border-gray-300">
                        [Map Component Placeholder - Leaflet JS]
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Save
                        Location</button>
                </div>
            </div>

            <!-- Media -->
            <div x-show="activeTab === 'media'" style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Logo (Square)</label>
                        <div
                            class="mt-2 flex items-center justify-center border-2 border-dashed border-gray-300 rounded-md h-32 hover:border-blue-400 cursor-pointer">
                            <span class="text-gray-400">Click to upload logo</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cover Image</label>
                        <div
                            class="mt-2 flex items-center justify-center border-2 border-dashed border-gray-300 rounded-md h-32 hover:border-blue-400 cursor-pointer">
                            <span class="text-gray-400">Click to upload cover</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Facilities Checklist</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500">
                            <span>Air Conditioner (AC)</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500">
                            <span>Free Wi-Fi</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500">
                            <span>Parking Area</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500">
                            <span>Prayer Room</span>
                        </label>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Save
                        Media</button>
                </div>
            </div>
        </div>
    </div>
@endsection