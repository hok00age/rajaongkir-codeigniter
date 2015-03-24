# RajaOngkir CodeIgniter
Merupakan library CodeIgniter untuk mengkonsumsi API dari [RajaOngkir](http://rajaongkir.com).
## Instalasi
Copy file sesuai dengan lokasinya masing-masing.
## Konfigurasi
Buka "**application/config/rajaongkir.php**" dan masukkan API key di sana.
## Contoh Penggunaan
### Memuat library
```php
$this->load->library('rajaongkir');
```
### Melakukan request
```php
//Mendapatkan semua propinsi
$provinces = $this->rajaongkir->province();

//Mendapatkan semua kota
$cities = $this->rajaongkir->city();

//Mendapatkan data ongkos kirim
$cost = $this->rajaongkir->cost(501, 114, 1000, "jne");
```
### Response
Response yang dihasilkan berupa string JSON balasan dari RajaOngkir.
### Dokumentasi lebih lanjut
Silakan lihat code di dalam library, di dalamnya terdapat komentar yang dapat membantu Anda.
### Referensi
[Dokumentasi RajaOngkir](http://rajaongkir.com/dokumentasi)