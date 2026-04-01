<!-- Card Component -->
@props(['title' => null, 'headerAction' => null])

<div class="bg-white rounded-lg shadow-md p-6">
    @if($title || $headerAction)
    <div class="flex items-center justify-between mb-4">
        @if($title)
        <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
        @endif
        @if($headerAction)
        <div>{{ $headerAction }}</div>
        @endif
    </div>
    @endif
    {{ $slot }}
</div>
