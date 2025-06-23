<!DOCTYPE html>
<html>
<head>
    <title>Loan Service Form</title>
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
                <strong>LOAN FORM</strong><br>
                <span>Loan Code: {{ $loan->code_loans }}</span><br>
                <span>Date: {{ \Carbon\Carbon::parse($loan->loan_date)->format('d-m-Y') }}</span>
            </td>
        </tr>
    </table>

    <table class="bordered">
        <tr>
            <td><strong>Name:</strong></td>
            <td>{{ $loan->loaner_name }}</td>
        </tr>
        <tr>
            <td><strong>Due Date:</strong></td>
            <td>{{ \Carbon\Carbon::parse($loan->return_date)->format('d-m-Y') }}</td>
        </tr>
    </table>

    <p class="section-title">Loaned Items</p>
    <table class="bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Item Name</th>
                <th>SN (Code)</th>
                <th>Qty</th>
                <th>Category</th>
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

    <p class="section-title">Notes</p>
    <table class="bordered">
        <tr>
            <td>{{ $loan->description ?: '-' }}</td>
        </tr>
    </table>

    <p class="section-title">Remark</p>
    <table class="bordered small">
        <tr>
            <td>
                Harap mengembalikan sebelum batas waktu,<br>
                Unit yang tidak dikembalikan lebih dari batas waktu,<br>
                akan diberlakukan denda!
            </td>
        </tr>
    </table>

    <table class="footer signatures">
        <tr>
            <td><strong>Admin</strong><br>{{ $loan->user->name ?? '-' }}</td>
            <td style="text-align: center;"><strong>Borrower</strong><br><br><br><br>({{ $loan->loaner_name }})</td>
            <td style="text-align: center;"><strong>CS</strong><br><br><br><br>(................................)</td>
        </tr>
    </table>

    <script>
        window.print();
    </script>

</body>
</html>
