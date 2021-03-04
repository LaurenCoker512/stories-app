<!DOCTYPE html>
<html>
<head>

</head>
<body>
    <h1>Create a New Story</h1>

    <form method="POST" action="/stories">
        @csrf
        
        <div>
            <label for="title">Title</label>

            <input type="text" name="title" id="title">
        </div>

        <div>
            <label for="description">Description</label>

            <input type="text" name="description" id="description">
        </div>

        <input type="submit" name="submit" value="Submit">
    </form>
</body>

</html>