<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        @page {
            margin: 100px 25px;
        }
    </style>
</head>

<body>

    <table style="border: 1px solid #000;">
        <tbody style="border: 2px solid #000; background: #fff;">
            <tr></tr>
            <tr></tr>
            <tr></tr>

            <tr>

                @foreach ($fotos as $index => $foto)
                @if ($index % 4 == 0 && $index > 0)
            </tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr>
                @endif

                <td style="width: 400px; height: 400px; overflow: hidden;">
                    @if(file_exists(public_path($foto)))
                    <img src="{{ public_path($foto) }}" height="400" width="400"
                        style="object-fit: cover; width: 100%; height: 100%;" />
                    @endif
                </td>

                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                @endforeach
            </tr>

            <tr></tr>
            <tr></tr>

            <tr>
                <td>
                    {{
                    'La información contenida en este documento es confidencial y de uso exclusivo de JPCONSTRUCRED'
                    }}
                </td>
            </tr>
        </tbody>
    </table>


</body>

</html>
