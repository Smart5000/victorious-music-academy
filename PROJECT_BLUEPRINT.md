# Victorious Victory Music Institute — Laravel Academy Blueprint

## Project Summary

Victorious Victory Music Institute is a professional children’s music learning academy for ages 4–17. Students register for free, Choose instruments, watch structured lessons, continue from their last video position, and see their learning progress. Admins manage the full curriculum and website content through FilamentPHP.

## Recommended Stack

- Laravel latest stable version
- Laravel Breeze for authentication
- Blade templates
- TailwindCSS
- Alpine.js for small interactions
- FilamentPHP for admin CMS
- MySQL database
- Local development with Laravel Herd or WampServer

## Core Roles

- `admin`: Manages website, curriculum, videos, students, and progress.
- `student`: Registers, logs in, watches lessons, and tracks learning progress.

## Installation Commands

Run these from the parent folder where the project should live:

```bash
composer create-project laravel/laravel victorious-victory-music-institute
cd victorious-victory-music-institute

composer require laravel/breeze --dev
php artisan breeze:install blade

npm install
npm run build

composer require filament/filament:"^3.0" -W
php artisan filament:install --panels

php artisan storage:link
```

Create the database in MySQL:

```sql
CREATE DATABASE victorious_victory_music;
```

Update `.env`:

```env
APP_NAME="Victorious Victory Music Institute"
DB_DATABASE=victorious_victory_music
DB_USERNAME=root
DB_PASSWORD=
```

Then run:

```bash
php artisan migrate --seed
php artisan serve
```

## Database Tables

### `users`

Laravel’s default users table plus:

- `id`: UUID primary key
- `name`
- `email`
- `password`
- `role`: `admin` or `student`
- `email_verified_at`

### `instruments`

- `id`: UUID
- `name`
- `slug`
- `description`
- `thumbnail_path`
- `status`: `active` or `coming_soon`
- `display_order`

Seed:

- Guitar: active
- Keyboard: active
- Violin: coming soon
- Drums: coming soon

### `categories`

Used for broad lesson grouping.

- `id`: UUID
- `name`
- `slug`
- `description`
- `display_order`

Examples:

- Beginner
- Intermediate

### `courses`

A structured curriculum under an instrument and category.

- `id`: UUID
- `instrument_id`
- `category_id`
- `title`
- `slug`
- `description`
- `thumbnail_path`
- `is_published`
- `display_order`

### `lessons`

- `id`: UUID
- `course_id`
- `title`
- `slug`
- `description`
- `thumbnail_path`
- `video_path`
- `video_url`
- `duration_seconds`
- `display_order`
- `is_free`
- `is_published`

Use `video_path` for uploaded videos and `video_url` for future external video providers.

### `lesson_progress`

- `id`: UUID
- `user_id`
- `lesson_id`
- `progress_percent`
- `watched_seconds`
- `last_position_seconds`
- `watched_50_at`
- `completed_at`
- `last_watched_at`

Add a unique index on:

- `user_id`
- `lesson_id`

### `site_settings`

Flexible CMS-managed website text.

- `id`: UUID
- `key`
- `value`
- `type`
- `group`

Examples:

- `site.name`
- `home.hero_title`
- `home.hero_subtitle`
- `home.cta_text`
- `home.about_text`
- `footer.text`
- `contact.email`

## Eloquent Relationships

```php
User hasMany LessonProgress
Instrument hasMany Course
Category hasMany Course
Course belongsTo Instrument
Course belongsTo Category
Course hasMany Lesson
Lesson belongsTo Course
Lesson hasMany LessonProgress
LessonProgress belongsTo User
LessonProgress belongsTo Lesson
```

## Route Structure

```php
Route::get('/', HomeController::class)->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/academy', [AcademyController::class, 'index'])->name('academy.index');
    Route::get('/academy/{instrument:slug}', [AcademyController::class, 'instrument'])->name('academy.instrument');
    Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/lessons/{lesson:slug}', [LessonController::class, 'show'])->name('lessons.show');
    Route::post('/lessons/{lesson}/progress', [LessonProgressController::class, 'store'])->name('lessons.progress.store');
});
```

Filament admin routes are handled by Filament, usually under:

```text
/admin
```

## Folder Structure

```text
app/
  Filament/
    Resources/
      CategoryResource.php
      CourseResource.php
      InstrumentResource.php
      LessonResource.php
      SiteSettingResource.php
      UserResource.php
    Pages/
      Dashboard.php
  Http/
    Controllers/
      AcademyController.php
      CourseController.php
      HomeController.php
      LessonController.php
      LessonProgressController.php
    Middleware/
      EnsureUserIsAdmin.php
  Models/
    Category.php
    Course.php
    Instrument.php
    Lesson.php
    LessonProgress.php
    SiteSetting.php
    User.php
database/
  migrations/
  seeders/
    AdminUserSeeder.php
    CategorySeeder.php
    CourseSeeder.php
    InstrumentSeeder.php
    LessonSeeder.php
    SiteSettingSeeder.php
resources/
  views/
    components/
      lesson-card.blade.php
      progress-bar.blade.php
      instrument-card.blade.php
    academy/
      index.blade.php
      instrument.blade.php
    courses/
      show.blade.php
    lessons/
      show.blade.php
    layouts/
      app.blade.php
    home.blade.php
routes/
  web.php
```

## Migration Scaffolding Commands

```bash
php artisan make:model Instrument -m
php artisan make:model Category -m
php artisan make:model Course -m
php artisan make:model Lesson -m
php artisan make:model LessonProgress -m
php artisan make:model SiteSetting -m

php artisan make:controller HomeController --invokable
php artisan make:controller AcademyController
php artisan make:controller CourseController
php artisan make:controller LessonController
php artisan make:controller LessonProgressController

php artisan make:middleware EnsureUserIsAdmin

php artisan make:seeder AdminUserSeeder
php artisan make:seeder InstrumentSeeder
php artisan make:seeder CategorySeeder
php artisan make:seeder CourseSeeder
php artisan make:seeder LessonSeeder
php artisan make:seeder SiteSettingSeeder
```

## Filament Resources

```bash
php artisan make:filament-resource Instrument --generate
php artisan make:filament-resource Category --generate
php artisan make:filament-resource Course --generate
php artisan make:filament-resource Lesson --generate
php artisan make:filament-resource User --generate
php artisan make:filament-resource LessonProgress --generate
php artisan make:filament-resource SiteSetting --generate
```

Recommended Filament features:

- FileUpload for thumbnails
- FileUpload for videos
- Select fields for instrument/category/course relationships
- Toggle for published status
- Select for instrument status: active/coming soon
- Reorderable lesson display order
- Read-only progress resource for student tracking

## Initial Model Patterns

Use UUIDs consistently:

```php
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Lesson extends Model
{
    use HasUuids;

    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'description',
        'thumbnail_path',
        'video_path',
        'video_url',
        'duration_seconds',
        'display_order',
        'is_free',
        'is_published',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'is_published' => 'boolean',
    ];
}
```

## Video Progress Logic

Frontend:

- Use an HTML5 `<video>` player for uploaded videos.
- Listen to `timeupdate`.
- Calculate percentage with `currentTime / duration * 100`.
- Send progress every 10–15 seconds.
- Send final progress on pause, ended, and page visibility change.

Backend rules:

- Only authenticated users can save progress.
- The server uses the authenticated user ID, never a submitted user ID.
- Progress should never decrease.
- Set `watched_50_at` when progress reaches 50%.
- Set `completed_at` when progress reaches 95% or greater.

## Lesson Page UI

Left side:

- Video player
- Lesson title
- Lesson description
- Progress bar
- Continue/next lesson button

Right side:

- Lesson table of contents
- Current lesson highlighted
- Completed lessons marked with a check icon
- 50% watched lessons marked as in progress

## Student Pages

- Homepage
- Academy dashboard
- Instrument page
- Course page
- Lesson watch page

Coming soon instruments should be visible but disabled.

## Admin Pages

- Dashboard overview
- Instruments
- Categories
- Courses
- Lessons
- Students
- Lesson progress
- Site settings

## Best Practices

- Keep admin logic in Filament resources.
- Keep student-facing logic in normal Laravel controllers.
- Use policies or middleware for admin-only access.
- Validate all admin forms.
- Store uploaded files in `storage/app/public`.
- Avoid hardcoding homepage text; load it from `site_settings`.
- Use reusable Blade components for cards, progress bars, and lesson lists.
- Keep progress tracking API-style so it can later be reused by a mobile app.
- Use route model binding by slug for public student pages.
- Use UUIDs for public-facing records.

## Future Mobile API Preparation

Later, add:

```text
routes/api.php
app/Http/Controllers/Api/
app/Http/Resources/
Laravel Sanctum authentication
```

Suggested future API endpoints:

- `GET /api/instruments`
- `GET /api/courses`
- `GET /api/lessons/{lesson}`
- `POST /api/lessons/{lesson}/progress`
- `GET /api/me/progress`

