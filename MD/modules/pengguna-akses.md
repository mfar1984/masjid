# Modul Pengguna & Akses

## Gambaran Keseluruhan

Modul Pengguna & Akses adalah bahagian penting dalam sistem E-Masjid yang menguruskan autentikasi, autoriti, dan kawalan akses untuk semua pengguna dalam sistem multi-masjid. Modul ini menggunakan Spatie Laravel Permission untuk pengurusan peranan dan kebenaran yang fleksibel.

## Struktur Hierarki Akses

### 1. Super Admin
- **Skop**: Akses penuh ke semua masjid dan sistem
- **Keupayaan**:
  - Menguruskan semua masjid dalam sistem
  - Menambah/membuang masjid baru
  - Menguruskan admin masjid
  - Akses ke semua log audit dan keselamatan
  - Konfigurasi sistem global

### 2. Admin Masjid
- **Skop**: Akses penuh ke masjid tertentu
- **Keupayaan**:
  - Menguruskan semua modul dalam masjid mereka
  - Menambah/menguruskan pengguna masjid
  - Akses ke laporan dan analitik masjid
  - Konfigurasi tetapan masjid

### 3. Pengguna Masjid
- **Skop**: Akses terhad mengikut peranan
- **Jenis Peranan**:
  - **Bendahari**: Akses modul kewangan
  - **Setiausaha**: Akses modul pengurusan dan komunikasi
  - **AJK**: Akses modul operasi dan program
  - **Penolong**: Akses baca sahaja ke modul tertentu

## Komponen Utama

### 1. Pengurusan Pengguna

#### Model User
```php
class User extends Authenticatable
{
    use HasRoles, HasPermissions;
    
    protected $fillable = [
        'name', 'email', 'phone', 'password'
    ];
    
    // Relationship dengan masjid
    public function masjids()
    {
        return $this->belongsToMany(Masjid::class)
                    ->withPivot('role', 'status', 'joined_at');
    }
}
```

#### Ciri-ciri Pengurusan Pengguna:
- Pendaftaran pengguna baru dengan validasi
- Kemaskini profil dan maklumat peribadi
- Pengurusan kata laluan dengan keselamatan tinggi
- Status pengguna (Aktif/Tidak Aktif/Digantung)
- Log aktiviti pengguna

### 2. Sistem Peranan (Roles)

#### Peranan Asas:
- **super-admin**: Akses penuh sistem
- **admin-masjid**: Admin untuk masjid tertentu
- **bendahari**: Pengurusan kewangan
- **setiausaha**: Pengurusan pentadbiran
- **ajk**: Ahli jawatankuasa
- **penolong**: Akses terhad

#### Pengurusan Peranan:
- Cipta peranan baru dengan kebenaran khusus
- Tugaskan peranan kepada pengguna
- Peranan boleh dikustomisasi untuk setiap masjid
- Hierarki peranan yang jelas

### 3. Sistem Kebenaran (Permissions)

#### Kategori Kebenaran:

**Pengurusan:**
- `view-kariah`, `create-kariah`, `edit-kariah`, `delete-kariah`
- `view-ajk`, `create-ajk`, `edit-ajk`, `delete-ajk`
- `view-asnaf`, `create-asnaf`, `edit-asnaf`, `delete-asnaf`

**Kewangan:**
- `view-transactions`, `create-transactions`, `edit-transactions`
- `view-donations`, `manage-donations`
- `view-reports`, `generate-reports`

**Operasi:**
- `view-programs`, `create-programs`, `edit-programs`
- `view-facilities`, `manage-bookings`
- `view-jenazah`, `manage-jenazah`

**Sistem:**
- `view-users`, `create-users`, `edit-users`, `delete-users`
- `view-logs`, `manage-settings`
- `view-masjid`, `create-masjid`, `edit-masjid`

### 4. Pengurusan Kumpulan

#### Struktur Kumpulan:
- **Kumpulan Pentadbiran**: Super Admin, Admin Masjid
- **Kumpulan Kewangan**: Bendahari, Penolong Bendahari
- **Kumpulan Operasi**: Setiausaha, AJK, Koordinator Program
- **Kumpulan Sokongan**: Penolong, Sukarelawan

#### Ciri-ciri Kumpulan:
- Penugasan peranan secara berkumpulan
- Komunikasi dalam kumpulan
- Laporan aktiviti kumpulan
- Hierarki kumpulan yang fleksibel

## Keselamatan dan Audit

### 1. Log Audit
- Rekod semua aktiviti pengguna
- Perubahan data dengan timestamp
- IP address dan user agent
- Aktiviti login/logout

### 2. Log Keselamatan
- Percubaan login yang gagal
- Akses tidak sah
- Perubahan kata laluan
- Aktiviti mencurigakan

### 3. Keselamatan Kata Laluan
- Minimum 8 karakter
- Kombinasi huruf, nombor, dan simbol
- Hashing menggunakan bcrypt
- Tempoh luput kata laluan

## API Endpoints

### Autentikasi
```
POST /api/login
POST /api/logout
POST /api/refresh
POST /api/register
```

### Pengurusan Pengguna
```
GET /api/users
POST /api/users
GET /api/users/{id}
PUT /api/users/{id}
DELETE /api/users/{id}
```

### Peranan dan Kebenaran
```
GET /api/roles
POST /api/roles
GET /api/permissions
POST /api/users/{id}/roles
DELETE /api/users/{id}/roles/{role}
```

## Implementasi Frontend

### Komponen Vue/Alpine.js
- UserManagement.vue
- RoleAssignment.vue
- PermissionMatrix.vue
- AuditLog.vue

### Halaman Utama
- `/admin/users` - Senarai pengguna
- `/admin/roles` - Pengurusan peranan
- `/admin/permissions` - Matriks kebenaran
- `/admin/groups` - Pengurusan kumpulan
- `/admin/audit` - Log audit
- `/admin/security` - Log keselamatan

## Konfigurasi dan Tetapan

### Environment Variables
```
AUTH_SESSION_TIMEOUT=120
PASSWORD_RESET_TIMEOUT=60
MAX_LOGIN_ATTEMPTS=5
LOCKOUT_DURATION=15
```

### Konfigurasi Spatie Permission
```php
// config/permission.php
'models' => [
    'permission' => App\Models\Permission::class,
    'role' => App\Models\Role::class,
],
'table_names' => [
    'roles' => 'roles',
    'permissions' => 'permissions',
    'model_has_permissions' => 'model_has_permissions',
    'model_has_roles' => 'model_has_roles',
    'role_has_permissions' => 'role_has_permissions',
],
```

## Testing

### Unit Tests
- UserTest.php
- RoleTest.php
- PermissionTest.php
- AuthTest.php

### Feature Tests
- UserManagementTest.php
- RoleAssignmentTest.php
- AuthenticationTest.php
- AuditLogTest.php

## Roadmap

### Fasa 1 (Semasa)
- ✅ Sistem autentikasi asas
- ✅ Pengurusan pengguna
- ✅ Peranan dan kebenaran asas

### Fasa 2 (Akan Datang)
- 🔄 Sistem multi-masjid
- 🔄 API untuk mobile app
- 🔄 Two-factor authentication

### Fasa 3 (Dirancang)
- 📋 Single Sign-On (SSO)
- 📋 LDAP integration
- 📋 Advanced audit reporting

---

**Nota**: Modul ini adalah asas untuk semua modul lain dalam sistem E-Masjid. Pastikan konfigurasi yang betul sebelum menggunakan modul lain.
