# AGENTS.md - SiPosyandu Jakarta

Development guidelines for agentic coding assistants working on this Laravel 13 + Tailwind CSS project.

## 📋 Project Overview

**Project:** SiPosyandu Jakarta - Sistem Informasi Manajemen Posyandu  
**Stack:** Laravel 13, PHP 8.3+, MySQL 8.0+, Tailwind CSS 4.x  
**Structure:** Monolith (pelita-hati/)  
**See:** `../prd.md` for complete product requirements

---

## 🛠️ Build & Development Commands

### Setup (First Time)
```bash
cd pelita-hati
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

### Development
```bash
# Start all services (server, queue, logs, vite)
composer run dev

# Or individual services
php artisan serve              # Backend server (localhost:8000)
php artisan queue:listen       # Queue worker
php artisan pail              # Real-time logs
npm run dev                   # Vite dev server (Tailwind)
```

### Testing
```bash
# Run all tests
composer run test
# or
php artisan test

# Run single test file
php artisan test tests/Feature/ExampleTest.php

# Run single test method
php artisan test --filter=test_the_application_returns_a_successful_response

# Run specific testsuite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# With coverage
php artisan test --coverage
```

### Linting & Formatting
```bash
# Format PHP code (Laravel Pint)
vendor/bin/pint

# Format specific file
vendor/bin/pint app/Models/User.php

# Check without fixing
vendor/bin/pint --test
```

### Database
```bash
# Run migrations
php artisan migrate

# Fresh migration (with seeders)
php artisan migrate:fresh --seed

# Rollback
php artisan migrate:rollback
```

---

## 📐 Code Style Guidelines

### PHP Conventions

**Naming:**
- Classes: `PascalCase` (e.g., `UserFactory`, `PosyanduController`)
- Methods: `camelCase` (e.g., `getUserData()`)
- Variables: `camelCase` (e.g., `$balitaData`, `$userRole`)
- Constants: `UPPER_SNAKE_CASE` (e.g., `STATUS_ACTIVE`)
- Models: Singular (e.g., `User`, `Balita`, `Posyandu`)
- Controllers: Suffix with `Controller` (e.g., `BalitaController`)

**Imports:**
```php
<?php

namespace App\Models;

// Framework imports first
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

// Then package imports
use Spatie\Permission\Traits\HasRoles;

// Then app imports
use App\Http\Traits\Auditable;

// Attributes last
use Illuminate\Database\Eloquent\Attributes\Fillable;
```

**Model Structure:**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Balita extends Model
{
    use HasFactory;

    // Fillable attributes
    #[Fillable(['name', 'birth_date', 'gender'])]
    
    // Casts
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'created_at' => 'datetime',
        ];
    }
    
    // Relationships
    public function posyandu(): BelongsTo
    {
        return $this->belongsTo(Posyandu::class);
    }
    
    // Scopes
    public function scopeActive($query): void
    {
        $query->where('status', 'active');
    }
    
    // Accessors/Mutators
    public function getAgeAttribute(): int
    {
        return $this->birth_date->diffInYears(now());
    }
}
```

**Error Handling:**
```php
// Use try-catch for external operations
try {
    $result = ExternalService::call();
} catch (\Exception $e) {
    Log::error('External service failed', ['error' => $e->getMessage()]);
    throw new ServiceException('Gagal memanggil layanan eksternal');
}

// Use validation in controllers
$request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users',
]);

// Use abort for HTTP errors
abort_if(!$user->can('edit'), 403, 'Unauthorized action');
```

### Tailwind CSS Conventions

**Color Scheme:**
```css
/* Primary: Green (Emerald) */
bg-primary-500    /* #10B981 */
bg-primary-600    /* #059669 - hover */
bg-primary-700    /* #047857 - active */

/* Secondary: Gray */
bg-secondary-100  /* #F3F4F6 */
bg-secondary-500  /* #6B7280 */
```

**Component Pattern:**
```html
<!-- Card -->
<div class="bg-white rounded-lg shadow-md p-6">
  <!-- Content -->
</div>

<!-- Button Primary -->
<button class="bg-primary-500 hover:bg-primary-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200">
  Simpan
</button>

<!-- Input Field -->
<input type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">

<!-- Table -->
<div class="overflow-x-auto">
  <table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
      <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
          Nama
        </th>
      </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
      <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap">Data</td>
      </tr>
    </tbody>
  </table>
</div>
```

**Responsive Breakpoints:**
```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
  <!-- Responsive grid -->
</div>
```

### Blade Conventions

**Component Usage:**
```blade
{{-- Layouts --}}
<x-layouts.app>
  <x-slot:title>Dashboard</x-slot:title>
  
  <x-card>
    <x-slot:header>Judul Card</x-slot:header>
    Content here
  </x-card>
</x-layouts.app>

{{-- Control structures --}}
@forelse($balitas as $balita)
  <x-balita-card :$balita />
@empty
  <x-empty-state message="Belum ada data balita" />
@endforelse

@can('edit', $balita)
  <x-button.edit :model="$balita" />
@endcan
```

---

## 📁 Project Structure

```
pelita-hati/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Feature-based (Admin/, Kader/, Nakes/)
│   │   ├── Middleware/      # CheckRole, AuditLog
│   │   └── Requests/        # Form validation
│   ├── Models/              # User, Balita, Posyandu, etc.
│   ├── Services/            # GiziCalculator, ReportGenerator
│   └── Traits/              # Auditable, HasUuid
├── database/
│   ├── migrations/          # Database schemas
│   ├── factories/           # Test data factories
│   └── seeders/             # Initial data
├── resources/
│   ├── views/
│   │   ├── layouts/         # app.blade.php, auth.blade.php
│   │   ├── components/      # Blade components
│   │   ├── landing/         # Landing page sections
│   │   ├── dashboards/      # Role-specific dashboards
│   │   └── admin/           # Admin CRUD views
│   └── css/
│       └── app.css          # Tailwind imports
├── routes/
│   ├── web.php              # Web routes
│   └── console.php          # Console routes
├── tests/
│   ├── Feature/             # Integration tests
│   └── Unit/                # Unit tests
└── vendor/                  # Dependencies (auto-generated)
```

---

## 🎯 Key Conventions

### Database
- All tables use `id` as primary key (auto-increment)
- Timestamps: `created_at`, `updated_at` (required for all tables)
- Foreign keys: Singular + `_id` (e.g., `posyandu_id`, `kelurahan_id`)
- Migration naming: `YYYY_MM_DD_description.php`

### API/Controllers
```php
// Resource controllers for CRUD
Route::resource('balitas', BalitaController::class);

// Single actions
Route::patch('balitas/{balita}/status', [BalitaController::class, 'updateStatus']);

// Request validation in FormRequest classes
public function store(StoreBalitaRequest $request): RedirectResponse
{
    $validated = $request->validated();
    $balita = Balita::create($validated);
    return redirect()->route('balitas.index');
}
```

### Authorization (Policies & Gates)
```php
// In Policy
public function update(User $user, Balita $balita): bool
{
    return $user->role === 'kader' && $user->posyandu_id === $balita->posyandu_id;
}

// Usage
if ($user->can('update', $balita)) {
    // Allowed
}
```

### Logging
```php
use Illuminate\Support\Facades\Log;

// Log levels
Log::info('Balita created', ['balita_id' => $balita->id]);
Log::warning('Gizi buruk detected', ['balita_id' => $balita->id]);
Log::error('Failed to calculate Z-Score', ['error' => $e->getMessage()]);
```

---

## 🚨 Common Pitfalls

1. **Don't** put business logic in controllers → Use Services
2. **Don't** use `request()` helper in models → Pass data explicitly
3. **Don't** forget to add foreign keys to `$fillable` if mass-assigning
4. **Always** use `DB::transaction()` for multi-step operations
5. **Always** add tests for new features (Feature tests preferred)
6. **Always** run `vendor/bin/pint` before committing

---

## 📝 Testing Guidelines

**Feature Test Example:**
```php
public function test_kader_can_create_balita(): void
{
    $user = User::factory()->kader()->create();
    
    $response = $this->actingAs($user)->post(route('balitas.store'), [
        'nik' => '1234567890123456',
        'name' => 'Test Baby',
        'birth_date' => '2024-01-01',
        'gender' => 'L',
    ]);
    
    $response->assertRedirect(route('balitas.index'));
    $this->assertDatabaseHas('balitas', ['nik' => '1234567890123456']);
}
```

**Unit Test Example:**
```php
public function test_z_score_calculation(): void
{
    $service = new GiziCalculator();
    
    $result = $service->calculateZScore(
        weight: 10.5,
        height: 85,
        ageMonths: 24,
        gender: 'L'
    );
    
    $this->assertEquals('normal', $result->status);
    $this->assertGreaterThan(-2, $result->zScore);
}
```

---

## 🔗 Useful Links

- Laravel Docs: https://laravel.com/docs/13.x
- Tailwind CSS: https://tailwindcss.com/docs
- PRD: `../prd.md` (Product Requirements)

---

**Last Updated:** April 2026  
**Laravel Version:** 13.x  
**PHP Version:** 8.3+
