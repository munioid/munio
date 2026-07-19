<style>
    .variables-container {
        padding: 1rem 0;
    }

    .variables-description {
        font-size: 14px;
        line-height: 1.6;
        color: #374151;
        margin-bottom: 24px;
    }

    .variables-description code {
        background: #f3f4f6;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        padding: 2px 6px;
        font-family: monospace;
        color: #dc2626;
    }

    .variables-table-wrapper {
        overflow-x: auto;
        border: 1px solid #d1d5db;
        border-radius: 8px;
    }

    .variables-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .variables-table thead {
        background: #f3f4f6;
    }

    .variables-table th {
        text-align: left;
        padding: 12px 16px;
        font-weight: 600;
        border-bottom: 1px solid #d1d5db;
    }

    .variables-table td {
        padding: 10px 16px;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: top;
    }

    .variables-table tbody tr:nth-child(even) {
        background: #fafafa;
    }

    .variables-table tbody tr:hover {
        background: #f5f5f5;
    }

    .variables-table code {
        background: #f3f4f6;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        padding: 2px 6px;
        font-family: monospace;
        color: #dc2626;
    }
</style>

<div class="variables-container">
    <div class="variables-description">
        Berikut adalah variable yang tersedia, yang bisa Anda gunakan untuk format nomor anggota.
        Gunakan variable dengan <code>@{{}}</code> (curly braces) untuk variable
        <code>data.custom_attribute</code> tanpa menggunakan spasi.
        Gunakan karakter tanpa <code>{{}}</code> untuk variable
        <code>#</code>, <code>$</code>, dan <code>*</code>.

        <br><br>

        <strong>Contoh:</strong><br>
        <code>####-$$$$-****-@{{index:4}}</code>

        <br><br>

        <strong>Preview:</strong><br>
        <code>ABCD-1234-A1B2-0001</code>
    </div>

    <div class="variables-table-wrapper">
        <table class="variables-table">
            <thead>
                <tr>
                    <th width="35%">Variable</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>#</code></td>
                    <td>Random Alphabet</td>
                </tr>

                <tr>
                    <td><code>$</code></td>
                    <td>Random Numeric</td>
                </tr>

                <tr>
                    <td><code>*</code></td>
                    <td>Random Alphanumeric</td>
                </tr>

                <tr>
                    <td><code>index:n</code></td>
                    <td>Nomor urut dengan format n digit (contoh @{{index:4}} : <code>0001</code>, <code>0002</code>).</td>
                </tr>

                @foreach ($attributes as $attribute)
                    <tr>
                        <td>
                            <code>{{ $attribute->fieldname }}</code>
                        </td>
                        <td>
                            {{ $attribute->label }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>