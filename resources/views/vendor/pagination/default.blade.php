@if ($paginator->hasPages())
    <center>
        @if($paginator->currentPage() > 2)
            <a href="{{ $paginator->url(1) }}">
                <img src="{{ asset('img/symbols/arrow_left_end.gif') }}" border="0" title="to the start">
            </a>
        @endif

        @if($paginator->currentPage() > 1)
            <a href="{{ $paginator->url($paginator->currentPage() - 1) }}">
                <img src="{{ asset('img/symbols/single_arrow_left.gif') }}" border="0" title="-1">
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span>{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <b>{{ $page }}</b>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if($paginator->currentPage() < $paginator->lastPage())
            <a href="{{ $paginator->url($paginator->currentPage() + 1) }}">
                <img src="{{ asset('img/symbols/single_arrow_right.gif') }}" border="0" title="+1">
            </a>
        @endif

        @if($paginator->currentPage() < $paginator->lastPage() - 1)
            <a href="{{ $paginator->url($paginator->lastPage()) }}">
                <img src="{{ asset('img/symbols/arrow_right_end.gif') }}" border="0" title="to the end">
            </a>
        @endif
    </center>
@endif
