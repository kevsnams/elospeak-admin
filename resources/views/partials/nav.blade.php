@section('header')
    <?php
        $page = isset($page) ? $page : 'home';

        $pages = [
            [
                'label' => 'Home',
                'link' => route('pages.index')
            ],
            [
                'label' => 'Students',
                'link' => route('students.index')
            ],
            [
                'label' => 'Teachers',
                'link' => route('teachers.index')
            ],
            [
                'label' => 'Messages',
                'link' => route('messages.index')
            ],
            [
                'label' => 'Settings',
                'link' => route('settings.index')
            ],
            [
                'label' => 'Applications',
                'link' => route('applications.index')
            ]
        ];
    ?>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="{{ route('pages.index') }}">ELOSpeak Admin</a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#top-nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="top-nav">
            <ul class="navbar-nav mr-auto">
                @foreach ($pages as $def)
                    <li class="nav-item {{ $page === strtolower($def['label']) ? 'active' : '' }}">
                        <a class="nav-link" href="{{ $def['link'] }}">{{ $def['label'] }}</a>
                    </li>
                @endforeach
            </ul>
            <div class="my-2 my-lg-0">
                <a href="{{ route('pages.unauth') }}">Logout</a>
            </div>
        </div>
    </nav>
@endsection
