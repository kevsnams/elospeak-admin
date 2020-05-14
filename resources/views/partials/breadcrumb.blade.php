<div class="container mt-2">
    <div class="d-flex">
        <div class="w-100">
            <ol class="breadcrumb">
                @foreach ($items as $item)
                    <li class="breadcrumb-item {{ $item['active'] ?? '' }}">
                        @if (isset($item['active']) && $item['active'] === true)
                            {{ $item['label'] }}
                        @else
                            <a href="{{ $item['route'] }}">{{ $item['label'] }}</a>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</div>


@section('css')
    @parent

    <style>
        .breadcrumb {
            background-color: #fff !important;
        }
    </style>
@endsection
