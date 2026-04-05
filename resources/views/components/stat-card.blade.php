<!-- Stat Card Component -->
@props(['title', 'value', 'icon' => null, 'color' => 'primary', 'trend' => null])

@php
$colorClasses = [
    'primary' => 'bg-primary-500',
    'secondary' => 'bg-gray-500',
    'success' => 'bg-green-500',
    'danger' => 'bg-red-500',
    'warning' => 'bg-yellow-500',
    'info' => 'bg-blue-500',
    'purple' => 'bg-purple-500',
];
@endphp

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $value }}</p>
            @if($trend)
            <p class="text-sm text-gray-500 mt-1">{{ $trend }}</p>
            @endif
        </div>
        @if($icon)
        <div class="{{ $colorClasses[$color] ?? 'bg-primary-500' }} p-3 rounded-lg">
            {!! $icon !!}
        </div>
        @endif
    </div>
</div>
