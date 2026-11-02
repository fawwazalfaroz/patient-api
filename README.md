# Patient API

## Tentang API

Patient API adalah layanan untuk menyimpan dan mengelola data pasien.

Cara kerjanya mirip pelayan di restoran. Ada tiga peran:
- **Pelanggan**: aplikasi yang dipakai petugas klinik.
- **Pelayan**: Patient API.
- **Dapur**: database, tempat data pasien disimpan.

Misalnya, pelanggan memesan ke pelayan: "Tolong ambilkan data pasien nomor 1." Pelayan membawa pesanan itu ke dapur, lalu kembali mengantarkan hasilnya: "Ini data pasien nomor 1 yang Anda minta."

Di dapur, tersimpan dua jenis data:
- **Data pasien**: nama, alamat, dan nomor telepon.
- **Detail pasien**: pekerjaan, tanggal lahir, serta nama, pekerjaan, dan alamat orang tua.

Menu yang bisa dipesan:
- melihat daftar semua pasien,
- melihat detail lengkap satu pasien,
- menambah pasien baru,
- mengubah data pasien,
- menghapus data pasien.

---

## Struktur Data

### Pasien (`patients`)

| Field | Tipe | Wajib | Contoh |
|---|---|---|---|
| id | integer | otomatis | 1 |
| name | string (maks. 255) | ya | Budi Santoso |
| address | text (maks. 255) | ya | Jl. Merdeka No. 10, Jakarta |
| phone | string (maks. 255) | ya | 081234567890 |

### Detail Pasien (`patient_details`)

| Field | Tipe | Wajib | Contoh |
|---|---|---|---|
| id | integer | otomatis | 1 |
| patient_id | integer | otomatis | 1 |
| occupation | string (maks. 255) | ya | Karyawan Swasta |
| date_of_birth | date (YYYY-MM-DD) | ya | 1995-08-17 |
| parent_name | string (maks. 255) | ya | Slamet Santoso |
| parent_occupation | string (maks. 255) | ya | Petani |
| parent_address | text (maks. 255) | ya | Jl. Melati No. 5, Bandung |

Setiap pasien memiliki tepat **satu** detail (relasi one-to-one). Jika pasien dihapus, detailnya ikut terhapus.

---

## Cara Menjalankan

### Kebutuhan

- PHP 8.2 atau lebih baru
- Composer
- MySQL
- Laravel 12 (terpasang otomatis lewat Composer)

### Langkah instalasi

1. Clone repository lalu masuk ke foldernya:
   ```bash
   git clone https://github.com/fawwazalfaroz/patient-api.git
   cd patient-api
   ```

2. Install dependency:
   ```bash
   composer install
   ```

3. Salin file konfigurasi dan buat application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Buat database baru bernama `patient_api`, lalu sesuaikan bagian database di file `.env` (hapus tanda `#` di depan barisnya):
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=patient_api
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   Sesuaikan port, username, dan password dengan MySQL di komputer Anda (MAMP biasanya memakai port `8889` dengan username dan password `root`).

5. Buat tabel sekaligus isi 10 data pasien contoh (dibuat acak dengan Faker) dan satu user `test@example.com`:
   ```bash
   php artisan migrate --seed
   ```

6. Jalankan server:
   ```bash
   php artisan serve
   ```
   API dapat diakses di `http://127.0.0.1:8000/api`.

---

## Daftar Endpoint

**Base URL:** `http://127.0.0.1:8000/api`

Sertakan header `Accept: application/json` di setiap request agar pesan error juga dikembalikan dalam format JSON.

| Method | URL | Fungsi |
|---|---|---|
| GET | `/patients` | Melihat daftar semua pasien (tanpa detail) |
| GET | `/patients/{id}` | Melihat detail lengkap satu pasien |
| POST | `/patients` | Menambah pasien baru beserta detailnya |
| PUT | `/patients/{id}` | Mengubah data pasien beserta detailnya |
| DELETE | `/patients/{id}` | Menghapus pasien beserta detailnya |

---

## Detail Endpoint

### 1. Melihat daftar semua pasien

Menampilkan semua pasien yang terdaftar. Yang ditampilkan hanya data utama (nama, alamat, dan nomor telepon), tanpa detail.

- **Method:** `GET`
- **URL:** `/api/patients`

**Jika berhasil (200):**
```json
[
  {
    "id": 1,
    "name": "Budi Santoso",
    "address": "Jl. Merdeka No. 10, Jakarta",
    "phone": "081234567890",
    "created_at": "2026-11-01T07:34:27.000000Z",
    "updated_at": "2026-11-01T07:34:27.000000Z"
  },
  {
    "id": 2,
    "name": "Siti Aminah",
    "address": "Jl. Kenanga No. 3, Bogor",
    "phone": "085711223344",
    "created_at": "2026-11-01T07:34:27.000000Z",
    "updated_at": "2026-11-01T07:34:27.000000Z"
  }
]
```

---

### 2. Melihat detail satu pasien

Menampilkan data lengkap satu pasien berdasarkan ID-nya, termasuk pekerjaan, tanggal lahir, dan data orang tuanya.

- **Method:** `GET`
- **URL:** `/api/patients/{id}`
- **Contoh:** `/api/patients/1`

**Jika berhasil (200):**
```json
{
  "id": 1,
  "name": "Budi Santoso",
  "address": "Jl. Merdeka No. 10, Jakarta",
  "phone": "081234567890",
  "created_at": "2026-11-01T07:34:27.000000Z",
  "updated_at": "2026-11-01T07:34:27.000000Z",
  "detail": {
    "id": 1,
    "patient_id": 1,
    "occupation": "Karyawan Swasta",
    "date_of_birth": "1995-08-17",
    "parent_name": "Slamet Santoso",
    "parent_occupation": "Petani",
    "parent_address": "Jl. Melati No. 5, Bandung",
    "created_at": "2026-11-01T07:34:27.000000Z",
    "updated_at": "2026-11-01T07:34:27.000000Z"
  }
}
```

**Jika gagal (404):** pasien dengan ID tersebut tidak ditemukan.
```json
{
  "message": "No query results for model [App\\Models\\Patient] 99"
}
```

---

### 3. Menambah pasien baru

Mendaftarkan pasien baru sekaligus detailnya dalam satu kali kirim.

- **Method:** `POST`
- **URL:** `/api/patients`
- **Header:** `Accept: application/json`, `Content-Type: application/json`

**Body (semua field wajib diisi):**
```json
{
  "name": "Budi Santoso",
  "address": "Jl. Merdeka No. 10, Jakarta",
  "phone": "081234567890",
  "occupation": "Karyawan Swasta",
  "date_of_birth": "1995-08-17",
  "parent_name": "Slamet Santoso",
  "parent_occupation": "Petani",
  "parent_address": "Jl. Melati No. 5, Bandung"
}
```

**Jika berhasil (201):**
```json
{
  "name": "Budi Santoso",
  "address": "Jl. Merdeka No. 10, Jakarta",
  "phone": "081234567890",
  "updated_at": "2026-11-01T07:34:27.000000Z",
  "created_at": "2026-11-01T07:34:27.000000Z",
  "id": 11,
  "detail": {
    "id": 11,
    "patient_id": 11,
    "occupation": "Karyawan Swasta",
    "date_of_birth": "1995-08-17",
    "parent_name": "Slamet Santoso",
    "parent_occupation": "Petani",
    "parent_address": "Jl. Melati No. 5, Bandung",
    "created_at": "2026-11-01T07:34:27.000000Z",
    "updated_at": "2026-11-01T07:34:27.000000Z"
  }
}
```

**Jika gagal (422):** ada data yang belum diisi atau formatnya salah. Contoh ketika `phone` tidak dikirim:
```json
{
  "message": "The phone field is required.",
  "errors": {
    "phone": [
      "The phone field is required."
    ]
  }
}
```

---

### 4. Mengubah data pasien

Memperbarui data pasien beserta detailnya berdasarkan ID. Semua field wajib dikirim ulang, termasuk yang tidak berubah.

- **Method:** `PUT`
- **URL:** `/api/patients/{id}`
- **Contoh:** `/api/patients/11`
- **Header:** `Accept: application/json`, `Content-Type: application/json`

**Body:** sama seperti saat menambah pasien (8 field, semuanya wajib).

**Jika berhasil (200):** data pasien beserta detail yang sudah diperbarui, dengan format yang sama seperti endpoint "Melihat detail satu pasien".

**Jika gagal:**
- **404**: pasien dengan ID tersebut tidak ditemukan.
- **422**: ada data yang belum diisi atau formatnya salah.

---

### 5. Menghapus pasien

Menghapus pasien berdasarkan ID. Detail pasien ikut terhapus secara otomatis.

- **Method:** `DELETE`
- **URL:** `/api/patients/{id}`
- **Contoh:** `/api/patients/11`

**Jika berhasil (200):**
```json
{
  "message": "Patient deleted successfully"
}
```

**Jika gagal (404):** pasien dengan ID tersebut tidak ditemukan.

---

## Arti Kode Status

| Kode | Arti | Kapan muncul |
|---|---|---|
| 200 | Permintaan berhasil | Melihat, mengubah, atau menghapus data |
| 201 | Data baru berhasil dibuat | Menambah pasien baru |
| 404 | Data tidak ditemukan | ID pasien yang diminta tidak ada |
| 422 | Data yang dikirim belum lengkap atau formatnya salah | Ada field wajib yang kosong, atau format tanggal tidak sesuai |