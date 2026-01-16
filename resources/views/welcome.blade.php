@extends('layouts.app')

@section('title', 'Mini CMS - Build Websites Fast')

@section('content')
<div class="min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6">Build Websites in Minutes</h1>
                <p class="text-xl mb-8 max-w-2xl mx-auto">
                    Mini CMS is a simple, powerful content management system that lets you create and manage websites without coding.
                </p>
                <div class="space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" 
                           class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100">
                            Go to Dashboard →
                        </a>
                    @else
                        <a href="{{ route('register') }}" 
                           class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100">
                            Get Started Free
                        </a>
                        <a href="{{ route('login') }}" 
                           class="inline-block border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600">
                            Sign In
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Everything You Need to Build</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-blue-500 text-3xl mb-4">🌐</div>
                    <h3 class="text-xl font-semibold mb-3">Multi-Site Management</h3>
                    <p class="text-gray-600">Create and manage multiple websites from a single dashboard.</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-green-500 text-3xl mb-4">📄</div>
                    <h3 class="text-xl font-semibold mb-3">Drag & Drop Pages</h3>
                    <p class="text-gray-600">Easily create and organize pages with our visual editor.</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-purple-500 text-3xl mb-4">🎨</div>
                    <h3 class="text-xl font-semibold mb-3">Beautiful Templates</h3>
                    <p class="text-gray-600">Start with professionally designed templates and customize as needed.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-6">Ready to Build Your Website?</h2>
            <p class="text-gray-600 mb-8">
                Join thousands of users who have created amazing websites with Mini CMS.
            </p>
            @auth
                <a href="{{ route('sites.create') }}" 
                   class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700">
                    Create Your First Site
                </a>
            @else
                <a href="{{ route('register') }}" 
                   class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700">
                    Start Free Today
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection