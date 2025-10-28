# HamroCDN PHP SDK

[![Lint & Test PR](https://github.com/HamroCDN/php-sdk/actions/workflows/prlint.yml/badge.svg)](https://github.com/HamroCDN/php-sdk/actions/workflows/prlint.yml)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=HamroCDN_php-sdk&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=HamroCDN_php-sdk)

> **Official PHP SDK for HamroCDN** — a simple, typed, and framework-agnostic way to upload, fetch, and manage files from your HamroCDN account.

---

## 📦 Installation

Install via [Composer](https://getcomposer.org/):

```bash
composer require hamrocdn/sdk
```

That’s it.  
No Laravel dependencies, no magic — just pure PHP.

---

## ⚙️ Configuration

You can pass your API key directly, or rely on environment/config values if available.

```php
use HamroCDN\HamroCDN;

$cdn = new HamroCDN('your-api-key');
```

Alternatively, if your environment has them:

```bash
export HAMROCDN_API_KEY="your-api-key"
```

the SDK automatically detects and uses them.

---

## 🚀 Usage

### 1. List Uploads (Paginated)

The `index()` method returns paginated results.  
You can provide pagination parameters such as `page` and `per_page`.

```php
$uploads = $cdn->index(page: 1, per_page: 10);

foreach ($uploads->all() as $upload) {
    echo $upload->getNanoId() . ' - ' . $upload->getOriginal()->getUrl() . PHP_EOL;
}
```

> Returns an object containing `data` (array of `Upload` models) and `meta` (pagination info).

Example of returned metadata:
```json
{
  "meta": {
    "total": 120,
    "per_page": 10,
    "page": 1
  }
}
```

---

### 2. Fetch a Single Upload

```php
$upload = $cdn->fetch('abc123');

echo $upload->getOriginal()->getUrl(); // https://hamrocdn.com/abc123/original
```

---

### 3. Upload a File

```php
$upload = $cdn->upload('/path/to/image.png');

echo $upload->getNanoId(); // nano ID of the uploaded file
```

---

### 4. Upload by Remote URL

```php
$upload = $cdn->uploadByURL('https://example.com/image.png');

echo $upload->getOriginal()->getUrl();
```

---

## 🧱 Models

### 🗂 `HamroCDN\Models\Upload`

| Property   | Type               | Description                          |
|------------|--------------------|--------------------------------------|
| `nanoId`   | `string`           | Unique identifier of the upload      |
| `user`     | `User` or `null`   | Owner of the file (if authenticated) |
| `deleteAt` | `string` or `null` | Deletion timestamp if temporary      |
| `original` | `File`             | File information (URL, size)         |

#### Methods
- `getNanoId()`: `string`
- `getUser()`: `?User`
- `getDeleteAt()`: `?string`
- `getOriginal()`: `File`
- `toArray()`: `array`

---

### 👤 `HamroCDN\Models\User`

| Property | Type     | Description           |
|----------|----------|-----------------------|
| `name`   | `string` | Name of the uploader  |
| `email`  | `string` | Email of the uploader |

#### Methods
- `getName()`: `string`
- `getEmail()`: `string`
- `toArray()`: `array`

---

### 🧾 `HamroCDN\Models\File`

| Property | Type     | Description        |
|----------|----------|--------------------|
| `url`    | `string` | Public CDN URL     |
| `size`   | `int`    | File size in bytes |

#### Methods
- `getUrl()`: `string`
- `getSize()`: `int`
- `toArray()`: `array`

---

## ⚡ Error Handling

All SDK errors extend `HamroCDN\Exceptions\HamroCDNException`.

Example:

```php
use HamroCDN\Exceptions\HamroCDNException;

try {
    $cdn->upload('/invalid/path.jpg');
} catch (HamroCDNException $e) {
    echo 'Upload failed: ' . $e->getMessage();
}
```

The SDK automatically wraps:
- Network issues (`GuzzleException`)
- Invalid JSON responses
- Missing API key or misconfiguration

---

## 🧪 Testing

The SDK is fully covered with [Pest](https://pestphp.com/) tests.

Run tests locally:

```bash
composer test
```

---

## 🪄 Framework Integrations

This SDK is **framework-agnostic**. If you’re using **Laravel**, check out the companion package:

👉 [**hamrocdn/laravel**](https://packagist.org/packages/hamrocdn/laravel)

It provides service providers, configuration publishing, and automatic Facade binding.

---

## 🧩 Type Safety/Static Analysis

- Fully typed with PHPStan annotations
- 100% PHP 8.0+ compatible
- Pint with `laravel` preset for code style
- Rector for automated refactoring
- SonarCloud integration for code quality

---

## 📄 License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

## 🤝 Contributing

Contributions are welcome! Please create a pull request or open an issue if you find any bugs or have feature requests.

## ⭐ Support

If you find this package useful, please consider starring the repository on GitHub to show your support.
