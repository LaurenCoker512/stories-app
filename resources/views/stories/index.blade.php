<!DOCTYPE html>
<html>
<head>

</head>
<body>
    <h1>Stories</h1>

    <ul>
        @forelse($stories as $story)
            <li>
                <a href="{{ $story->path() }}">{{ $story->title }}</a>
            </li>
        @empty
            <li>There are no stories yet. Create one here!</li>
        @endforelse
    </ul>
</body>

</html>