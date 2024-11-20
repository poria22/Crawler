<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
<div class="container mt-4">
    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">عنوان</th>
                <th scope="col">توضیحات</th>
                <th scope="col">محتوی</th>
                <th scope="col">تاریخ ایجاد</th>
            </tr>
            </thead>
            <tbody>
            @foreach($crawlers as $crawler)
                <tr>
                    <th scope="row">#</th>
                    <td>{{ $crawler->title }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($crawler->description, 50) }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($crawler->content, 150) }}</td>
                    <td>{{ verta($crawler->create_at) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
