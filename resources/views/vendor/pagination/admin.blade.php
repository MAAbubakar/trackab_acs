@if ($paginator->hasPages())
    <style>
        .admin-pagination-wrap {
            margin-top: 24px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .admin-pagination-summary {
            color: #475569;
            font-size: .92rem;
            font-weight: 750;
        }

        .admin-pagination {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .admin-pagination li {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .admin-pagination a,
        .admin-pagination span {
            min-width: 40px;
            height: 40px;
            padding: 0 13px;
            border-radius: 13px;
            border: 1px solid #dbe7e2;
            background: #ffffff;
            color: #0f172a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: .92rem;
            font-weight: 900;
            box-shadow: 0 8px 18px rgba(15, 23, 42, .04);
        }

        .admin-pagination a:hover {
            background: #ecfdf5;
            border-color: #0b6b57;
            color: #0b6b57;
        }

        .admin-pagination .active span {
            background: #0b6b57;
            border-color: #0b6b57;
            color: #ffffff;
        }

        .admin-pagination .disabled span {
            background: #f8fafc;
            color: #94a3b8;
            cursor: not-allowed;
        }

        .admin-pagination .dots span {
            border-color: transparent;
            box-shadow: none;
            background: transparent;
            color: #64748b;
        }

        @media (max-width: 640px) {
            .admin-pagination-wrap {
                align-items: center;
                text-align: center;
            }

            .admin-pagination {
                justify-content: center;
            }

            .admin-pagination a,
            .admin-pagination span {
                min-width: 36px;
                height: 36px;
                padding: 0 10px;
                font-size: .85rem;
            }
        }
    </style>

    <div class="admin-pagination-wrap">
        <div class="admin-pagination-summary">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </div>

        <ul class="admin-pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled"><span>&lsaquo;</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="dots"><span>{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a></li>
            @else
                <li class="disabled"><span>&rsaquo;</span></li>
            @endif
        </ul>
    </div>
@endif
