# Google Sheets Webhook

File ini berisi skrip Google Apps Script yang dapat Anda deploy sendiri sebagai Web App untuk menerima data pesanan otomatis dari website.

## Cara pakai

1. Buka Google Sheets baru.
2. Klik `Extensions` > `Apps Script`.
3. Hapus semua kode yang ada, lalu tempelkan kode di bawah.
4. Ganti `SPREADSHEET_ID` dengan ID spreadsheet Anda.
5. Simpan.
6. Klik `Deploy` > `New deployment`.
7. Pilih `Web app`.
   - Execute as: `Me`
   - Who has access: `Anyone`
8. Salin `Web app URL`.
9. Masukkan URL tersebut ke `Google Sheets Webhook URL` di admin dashboard.
10. Masukkan juga `Spreadsheet URL` dari Google Sheets Anda.

## Kode Google Apps Script

```javascript
function doPost(e) {
  try {
    var payload = JSON.parse(e.postData.contents);
    var spreadsheetId = 'SPREADSHEET_ID'; // Ganti dengan ID spreadsheet Anda
    var ss = SpreadsheetApp.openById(spreadsheetId);
    var sheet = ss.getSheetByName('Sheet1');
    if (!sheet) {
      sheet = ss.insertSheet('Sheet1');
    }

    if (sheet.getLastRow() === 0) {
      sheet.appendRow([
        'Waktu',
        'Aksi',
        'No. Registrasi',
        'Nama Instansi',
        'Nama Kontak',
        'No. Telepon',
        'Email',
        'Alamat',
        'Layanan',
        'Metode',
        'Status',
        'Tanggal Request',
        'Link Admin',
        'Certificate Number',
        'Admin Note'
      ]);
    }

    if (payload.action === 'create') {
      sheet.appendRow([
        new Date(),
        'CREATE',
        payload.registration_number || '',
        payload.nama_instansi || '',
        payload.nama_kontak || '',
        payload.nomor_telepon || '',
        payload.email || '',
        payload.alamat_lengkap || '',
        payload.device_name || '',
        payload.metode_kalibrasi || '',
        payload.status || '',
        payload.request_date || '',
        payload.link_admin || '',
        '',
        ''
      ]);
    } else if (payload.action === 'update') {
      sheet.appendRow([
        new Date(),
        'UPDATE',
        payload.registration_number || '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        payload.status || '',
        '',
        payload.link_admin || '',
        payload.certificate_number || '',
        payload.admin_note || ''
      ]);
    } else if (payload.action === 'delete') {
      var rows = sheet.getDataRange().getValues();
      for (var i = rows.length - 1; i >= 1; i--) {
        if (rows[i][2] === payload.registration_number) {
          sheet.deleteRow(i + 1);
        }
      }
    }

    return ContentService.createTextOutput(JSON.stringify({ok: true}))
      .setMimeType(ContentService.MimeType.JSON);
  } catch (err) {
    return ContentService.createTextOutput(JSON.stringify({ok: false, error: err.message}))
      .setMimeType(ContentService.MimeType.JSON);
  }
}
```

## Contoh URL yang harus Anda simpan

- `Spreadsheet URL`: `https://docs.google.com/spreadsheets/d/YOUR_SPREADSHEET_ID/edit#gid=0`
- `Google Sheets Webhook URL`: URL `Web app` yang Anda dapatkan setelah deploy.

> Saya tidak bisa menghasilkan URL webhook Google Apps Script dari sini karena memerlukan akun Google Anda. Anda harus deploy sendiri lalu copy/paste URL tersebut.
