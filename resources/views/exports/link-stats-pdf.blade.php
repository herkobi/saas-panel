<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Link İstatistikleri - {{ $link['alias'] }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .summary {
            margin-bottom: 30px;
        }

        h1,
        h2,
        h3 {
            color: #2563eb;
        }

        .section {
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Link İstatistikleri Raporu</h1>
        <h2>{{ $link['alias'] }}</h2>
        <p>{{ $dateRange['from'] }} - {{ $dateRange['to'] }} tarihleri arasında</p>
    </div>

    <div class="summary">
        <h3>Özet Bilgiler</h3>
        <table>
            <tr>
                <th>Kısa URL</th>
                <td>{{ $link['alias'] }}</td>
            </tr>
            <tr>
                <th>Hedef URL</th>
                <td>{{ $link['url'] }}</td>
            </tr>
            <tr>
                <th>Toplam Tıklama</th>
                <td>{{ $totalClicks }}</td>
            </tr>
            <tr>
                <th>Benzersiz Tıklama</th>
                <td>{{ $uniqueClicks }}</td>
            </tr>
            <tr>
                <th>Dönüşüm Oranı</th>
                <td>{{ $conversionRate }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3>Günlük Tıklama İstatistikleri</h3>
        <table>
            <thead>
                <tr>
                    <th>Tarih</th>
                    <th>Tıklama Sayısı</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dailyClicks as $date => $count)
                    <tr>
                        <td>{{ $date }}</td>
                        <td>{{ $count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <div class="section">
        <h3>Tarayıcı İstatistikleri</h3>
        <table>
            <thead>
                <tr>
                    <th>Tarayıcı</th>
                    <th>Tıklama Sayısı</th>
                    <th>Yüzde (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($browserStats as $browser)
                    <tr>
                        <td>{{ $browser['value'] }}</td>
                        <td>{{ $browser['count'] }}</td>
                        <td>{{ $browser['percentage'] }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Platform İstatistikleri</h3>
        <table>
            <thead>
                <tr>
                    <th>Platform</th>
                    <th>Tıklama Sayısı</th>
                    <th>Yüzde (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($platformStats as $platform)
                    <tr>
                        <td>{{ $platform['value'] }}</td>
                        <td>{{ $platform['count'] }}</td>
                        <td>{{ $platform['percentage'] }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Ülke İstatistikleri</h3>
        <table>
            <thead>
                <tr>
                    <th>Ülke</th>
                    <th>Tıklama Sayısı</th>
                    <th>Yüzde (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($countryStats as $country)
                    <tr>
                        <td>{{ $country['value'] }}</td>
                        <td>{{ $country['count'] }}</td>
                        <td>{{ $country['percentage'] }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Rapor oluşturma tarihi: {{ date('Y-m-d H:i:s') }}</p>
    </div>
</body>

</html>
