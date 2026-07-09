@props(['column', 'label', 'sort', 'direction'])

@php
    $isActive = $sort === $column;
    $nextDirection = $isActive && $direction === 'asc' ? 'desc' : 'asc';
    $queryParams = array_merge(request()->query(), ['sort' => $column, 'direction' => $nextDirection]);
@endphp

<th>
    <a href="{{ route(request()->route()->getName(), $queryParams) }}"
       style="display:inline-flex;align-items:center;gap:4px;color:inherit;text-decoration:none;">
        {{ $label }}
        @if($isActive)
            <i class="ti ti-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}" style="font-size:12px;"></i>
        @else
            <i class="ti ti-arrows-sort" style="font-size:12px;opacity:0.35;"></i>
        @endif
    </a>
</th>