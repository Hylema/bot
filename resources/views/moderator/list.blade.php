<!DOCTYPE HTML>
<head>
</head>
<body>
<h3>Документ #{{ $license->id }}</h3>
@foreach(json_decode($license->photo_path) as $photo_path)
    <img src="{{ asset('storage/'.$photo_path) }}" />
@endforeach

<form action="/licenses/{{ $license->id }}/process" method="POST">
    {{ csrf_field() }}

    Серия<br>
    <input type="text" name="series" value=""><br><br>

    Номер<br>
    <input type="text" name="number" value=""><br><br>

    Дата выдачи<br>
    <input type="text" name="issuance_date" value=""><br><br>

    <input type="submit" name="Сохранить">
</form>

</body>
