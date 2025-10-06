<!DOCTYPE html>
<html lang="en">
<head>
    <title>Explore | TripMate</title>
    <x-tourist.head />
</head>
<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex flex-col">

@php
    use Illuminate\Support\Facades\Auth;
    $tourist = Auth::guard('tourist')->user();
@endphp

<x-tourist.header />

@livewire('explore-activities')

<x-tourist.footer />

@livewireScripts

</body>
</html>