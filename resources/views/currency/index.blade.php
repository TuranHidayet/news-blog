<!DOCTYPE html>
<html>
<head>
    <title>Valyuta Məzənnələri</title>
</head>
<body>
    <h1>Valyuta Məzənnələri (AZN)</h1>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Valyutadan</th>
                <th>Valyutaya</th>
                <th>Kurs</th>
                <th>Son Yenilənmə</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rates as $rate)
                <tr>
                    <td>{{ $rate->currency_from }}</td>
                    <td>{{ $rate->currency_to }}</td>
                    <td>{{ $rate->rate }}</td>
                    <td>{{ $rate->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
