<!DOCTYPE html>
<html>

<head>
    <title>Laporan Arsip</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .header p {
            margin: 5px 0;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Arsip Dokumen</h2>
        <p>Dicetak pada: {{ date('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Dokumen</th>
                <th>Kategori</th>
                <th>Sumber</th>
                <th>Tanggal</th>
                <th>Lokasi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($documents as $index => $doc)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $doc->document_number }}</td>
                    <td>{{ $doc->category }}</td>
                    <td>{{ strtoupper($doc->source) }}</td>
                    <td>{{ \Carbon\Carbon::parse($doc->document_date)->format('d-m-Y') }}</td>
                    <td>{{ $doc->cabinet }}/{{ $doc->shelf }}/{{ $doc->box }}</td>
                    <td>{{ ucfirst($doc->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
