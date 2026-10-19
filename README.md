# 🎯 Pinspire API

REST API backend untuk aplikasi mobile **Pinspire** (kloning Pinterest) — UTS Pemrograman Aplikasi Bergerak.

## Tech Stack

- **Laravel 13** + **Sanctum** (token auth)
- **MySQL** (via Laragon)
- **PHP 8.2+**

## Cara Menjalankan

```bash
# 1. Pastikan Laragon sudah menyala (Apache + MySQL)

# 2. Install dependencies
composer install

# 3. Salin .env
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi database di .env
# DB_CONNECTION=mysql
# DB_DATABASE=pinspire
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Buat database
mysql -u root -e "CREATE DATABASE IF NOT EXISTS pinspire;"

# 6. Jalankan migration + seeder
php artisan migrate:fresh --seed

# 7. Buat storage link (untuk upload gambar)
php artisan storage:link

# 8. Jalankan server
php artisan serve --port=8001
```

API tersedia di: `http://127.0.0.1:8001/api/`

## Akun Demo

| Email | Password |
|-------|----------|
| `demo@pinspire.com` | `demo1234` |

## Daftar Endpoint API

### Auth (Publik)
| Method | URL | Keterangan |
|--------|-----|------------|
| `POST` | `/api/auth/check-email` | Cek email terdaftar |
| `POST` | `/api/auth/register` | Registrasi akun baru |
| `POST` | `/api/auth/login` | Login |

### Feed (Publik)
| Method | URL | Keterangan |
|--------|-----|------------|
| `GET` | `/api/pins/feed` | Feed beranda (paginated) |
| `GET` | `/api/pins/{id}/related` | Pin terkait |

### Pin (Butuh Token)
| Method | URL | Keterangan |
|--------|-----|------------|
| `POST` | `/api/pins` | Upload pin baru (multipart) |
| `POST` | `/api/pins/{id}/like` | Like pin |
| `DELETE` | `/api/pins/{id}/like` | Unlike pin |
| `POST` | `/api/pins/{id}/save` | Simpan pin |
| `DELETE` | `/api/pins/{id}/save` | Batal simpan |
| `GET` | `/api/pins/saved` | Daftar pin tersimpan |

### Board (Butuh Token)
| Method | URL | Keterangan |
|--------|-----|------------|
| `GET` | `/api/boards` | Daftar papan |
| `POST` | `/api/boards` | Buat papan baru |
| `DELETE` | `/api/boards/{id}` | Hapus papan |
| `POST` | `/api/boards/{id}/pins/{pin}` | Tambah pin ke papan |
| `DELETE` | `/api/boards/{id}/pins/{pin}` | Hapus pin dari papan |

### Profil (Butuh Token)
| Method | URL | Keterangan |
|--------|-----|------------|
| `GET` | `/api/me` | Data profil |
| `PATCH` | `/api/me` | Edit profil |

### Auth (Butuh Token)
| Method | URL | Keterangan |
|--------|-----|------------|
| `POST` | `/api/auth/logout` | Logout |
| `GET` | `/api/auth/me` | Data user |

## Contoh Request

### Login
```bash
curl -X POST http://127.0.0.1:8001/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"demo@pinspire.com","password":"demo1234"}'
```

### Feed (dengan token)
```bash
curl http://127.0.0.1:8001/api/pins/feed \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Struktur Database

```
users              — Data pengguna
user_interests     — Minat pengguna (many-to-one)
pins               — Pin/gambar yang diupload
boards             — Papan koleksi
board_pins         — Pin di dalam papan (many-to-many)
saved_pins         — Pin yang disimpan user (many-to-many)
pin_likes          — Like pada pin (many-to-many)
personal_access_tokens — Token Sanctum
```
