@props([
    'type' => 'info', // info, success, warning, error
    'title' => null,
    'dismissible' => false,
])

@php
$colors = [
    'info' => 'bg-blue-50 border-blue-200 text-blue-800',
    'success' => 'bg-green-50 border-green-200 text-green-800',
    'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
    'error' => 'bg-red-50 border-red-200 text-red-800',
];
$borderColors = [
    'info' => 'border-l-blue-500',
    'success' => 'border-l-green-500',
    'warning' => 'border-l-yellow-500',
    'error' => 'border-l-red-500',
];
$icons = [
    'info' => '📋',
    'success' => '✅',
    'warning' => '⚠️',
    'error' => '🚨',
];
@endphp

<div class="border-l-4 {{ $borderColors[$type] }} {{ $colors[$type] }} rounded-r-lg p-4 {{ $dismissible ? 'relative pr-10' : '' }}" role="alert">
    @if($title)
    <div class="flex items-center mb-2">
        <span class="text-xl mr-2">{{ $icons[$type] }}</span>
        <h3 class="font-semibold text-lg">{{ $title }}</h3>
    </div>
    @endif
    
    <div class="{{ $title ? 'ml-8' : 'flex items-center' }}">
        @if(!$title)
        <span class="text-xl mr-2">{{ $icons[$type] }}</span>
        @endif
        <div class="{{ !$title ? 'flex items-center' : '' }}">
            {{ $slot }}
        </div>
    </div>
    
    @if($dismissible)
    <button type="button" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700" onclick="this.parentElement.remove()">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
    @endif
</div>
