@if(session('success'))
    <div class="bg-gradient-to-r from-amber-50 to-yellow-50 text-amber-800 p-4 rounded-xl mb-6 border-l-4 border-amber-500 shadow-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-amber-500 mr-3"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    </div>
@endif
