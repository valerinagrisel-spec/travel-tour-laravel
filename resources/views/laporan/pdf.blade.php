<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><style>body{font-family:DejaVu Sans,sans-serif;font-size:10px;color:#252944}h1{font-size:18px}table{width:100%;border-collapse:collapse;margin-top:18px}th,td{border:1px solid #bbb;padding:7px;text-align:left}th{background:#dce9fb}tfoot td{font-weight:bold}</style></head><body>
<h1>Travel Tour — Laporan Transaksi</h1><p>Periode: {{ $dari }} sampai {{ $sampai }}</p><p>Jumlah transaksi terverifikasi: {{ $jumlah }}</p>
<table><thead><tr><th>No</th><th>Tanggal</th><th>Kode</th><th>Pelanggan</th><th>Paket</th><th>Invoice</th><th>Nominal</th></tr></thead><tbody>
@foreach($transaksi as $i => $p)<tr><td>{{ $i+1 }}</td><td>{{ $p->tanggal_bayar?->format('d/m/Y') }}</td><td>{{ $p->pemesanan?->kode_pemesanan }}</td><td>{{ $p->pemesanan?->pemesan?->name }}</td><td>{{ $p->pemesanan?->paket?->nama_paket }}</td><td>{{ $p->nomor_invoice }}</td><td>Rp {{ number_format($p->nominal,0,',','.') }}</td></tr>@endforeach
</tbody><tfoot><tr><td colspan="6">TOTAL</td><td>Rp {{ number_format($total,0,',','.') }}</td></tr></tfoot></table>
<p>Dokumen dihasilkan oleh sistem Travel Tour.</p></body></html>
