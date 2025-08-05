<!DOCTYPE html>
<html>
<head>
    <title>Formulir Peminjaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            padding: 6px;
            text-align: left;
        }
        .header, .footer {
            width: 100%;
            margin-top: 20px;
        }
        .header td, .footer td {
            padding: 4px;
        }
        .bordered {
            border: 1px solid #000;
        }
        .section-title {
            margin-top: 15px;
            font-weight: bold;
        }
        .signatures td {
            padding-top: 50px;
        }
        .small {
            font-size: 11px;
            color: #555;
        }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td><strong>SMKN 5 Bandung</strong><br>Jl. Bojong Koneng No.37, Bandung<br>Telp: (022) xxxx xxxx</td>
            <td style="text-align: right;">
                <strong>FORMULIR PEMINJAMAN</strong><br>
                <span>Kode Peminjaman: {{ $loan->code_loans }}</span><br>
                <span>Tanggal: {{ \Carbon\Carbon::parse($loan->loan_date)->format('d-m-Y') }}</span>
            </td>
        </tr>
    </table>

    <table class="bordered">
        <tr>
            <td><strong>Nama Peminjam:</strong></td>
            <td>{{ $loan->loaner_name }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Jatuh Tempo:</strong></td>
            <td>{{ \Carbon\Carbon::parse($loan->return_date)->format('d-m-Y') }}</td>
        </tr>
    </table>

    <p class="section-title">Barang yang Dipinjam</p>
    <table class="bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kode/SN</th>
                <th>Jumlah</th>
                <th>Kategori</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loan->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->pivot->quantity }}</td>
                    <td>{{ $item->category->name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="section-title">Catatan</p>
    <table class="bordered">
        <tr>
            <td>{{ $loan->description ?: '-' }}</td>
        </tr>
    </table>

    <p class="section-title">Keterangan</p>
    <table class="bordered small">
        <tr>
            <td>
                Harap mengembalikan barang sebelum tanggal jatuh tempo.<br>
                Barang yang tidak dikembalikan melebihi batas waktu<br>
                akan dikenakan denda sesuai peraturan yang berlaku.
            </td>
        </tr>
    </table>

    <table class="footer signatures">
        <tr>
            <td><strong>Admin</strong><br>{{ $loan->user->name ?? '-' }}</td>
            <td style="text-align: center;"><strong>Peminjam</strong><br><br><br><br>({{ $loan->loaner_name }})</td>
            <td style="text-align: center;"><strong>Pihak CS</strong><br><br><br><br>(................................)</td>
        </tr>
    </table>

    <script>
        window.print();
    </script>

</body>
</html>