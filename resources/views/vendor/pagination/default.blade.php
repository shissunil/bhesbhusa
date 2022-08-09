@if ($paginator->hasPages())
    <div class="custom-pagination">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-number prev disabled">
                    <a href="javascript:;">Prev</a>
                </li>
            @else
                <li class="page-number prev">
                    <a href="{{ $paginator->previousPageUrl() }}">Prev</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-number disabled"><a href="javascript:;">{{ $element }}</a></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-number active"><a href="{{ $url }}">{{ $page }}</a></li>
                        @else
                            <li class="page-number"><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-number prev">
                    <a href="{{ $paginator->nextPageUrl() }}">Next</a>
                </li>
            @else
                <li class="page-number prev disabled">
                    <a href="javascript:;">Next</a>
                </li>
            @endif
        </ul>
    </div>
@endif
