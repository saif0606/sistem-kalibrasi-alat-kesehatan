# Google Sheets Integration

This project can push calibration order data into Google Sheets using a webhook.

## What to configure

1. In admin dashboard, set:
   - `Spreadsheet URL`
   - `Google Sheets Webhook URL`

2. The webhook URL should be a Google Apps Script web app URL.

## Example Google Apps Script

```javascript
function doPost(e) {
  try {
    var data = JSON.parse(e.postData.contents);
    var sheet = SpreadsheetApp.openById('SPREADSHEET_ID').getSheetByName('Sheet1');
    if (!sheet) {
      throw new Error('Sheet1 not found');
    }

    if (data.action === 'create') {
      sheet.appendRow([
        new Date(),
        data.registration_number || '',
        data.nama_instansi || '',
        data.nama_kontak || '',
        data.nomor_telepon || '',
        data.email || '',
        data.alamat_lengkap || '',
        data.device_name || '',
        JSON.stringify(data.daftar_alat || []),
        data.status || '',
        data.request_date || '',
        data.link_admin || ''
      ]);
    } else if (data.action === 'update') {
      sheet.appendRow([
        new Date(),
        'UPDATE',
        data.registration_number || '',
        data.status || '',
        data.certificate_number || '',
        data.admin_note || '',
        data.link_admin || ''
      ]);
    }

    return ContentService.createTextOutput(JSON.stringify({ok:true}))
      .setMimeType(ContentService.MimeType.JSON);
  } catch (err) {
    return ContentService.createTextOutput(JSON.stringify({ok:false, error:err.message}))
      .setMimeType(ContentService.MimeType.JSON);
  }
}
```

## Notes

- `create` is sent when a user creates a new calibration order.
- `update` is sent when an admin updates an order status.
- If you want to update an existing row instead of appending, modify the script to search by `registration_number`.
