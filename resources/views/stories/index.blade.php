<!DOCTYPE html>
<html>
<head>

</head>
<body>
    <h1>Stories</h1>

    <ul>
        @foreach($stories as $story)
            <li>{{ $story->title }}</li>
        @endforeach
    </ul>
</body>

</html>