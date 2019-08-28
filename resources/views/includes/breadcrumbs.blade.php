<div class="uk-padding-small">
    <ul class="uk-breadcrumb">
        @foreach ($trails as $trail)
            <li>
                @if (isset($trail['is_active']) && $trail['is_active'])
                    <span>
                @else
                    <a href="{{ $trail['href'] }}">
                @endif
                        
                        {{ $trail['text']}}

                @if (isset($trail['is_active']) && $trail['is_active'])
                    </span>
                @else
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
</div>