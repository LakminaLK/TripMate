<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 lg:p-12">
        <h1 class="text-3xl font-bold mb-4">Welcome, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-700 text-lg">You are logged in as a Tourist.</p>

        <div class="mt-8">
            <a href="#" class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Explore Trips</a>
        </div>
    </div>
</x-app-layout>
