# Huntglitch - Laravel Error & Query Logger Package

**Huntglitch** is a development monitoring platform that can help you instantly solve bugs, broken codes and avoid crashes and false API calls with ease. Huntglitch gives you answers, not hints.

You can use Huntglitch to find out any errors or bugs in the critical codes of your app or software, quickly find out bugs, prevent lags due to rising user requests by forecasting future demand and quickly monitor the overall health of your app or software with ease.  


<p>  
<a href="https://packagist.org/search/?tags=logging" target="_blank" rel="noopener noreferrer">#Logging</a>&nbsp;  
<a href="https://packagist.org/search/?tags=debugging" target="_blank" rel="noopener noreferrer">#Debugging</a>&nbsp;  
<a href="https://packagist.org/search/?tags=laravel" target="_blank" rel="noopener noreferrer">#Laravel</a>&nbsp;  
<a href="https://packagist.org/search/?tags=php" target="_blank" rel="noopener noreferrer">#PHP</a>&nbsp;  
<a href="https://packagist.org/search/?tags=monitoring" target="_blank" rel="noopener noreferrer">#Monitoring</a>&nbsp;  
<a href="https://packagist.org/search/?tags=query-logger" target="_blank" rel="noopener noreferrer">#QueryLogger</a>  
</p>

## Documentation

- [Features](#features)  
- [Supported Versions](#supported-versions)  
- [Installation](#installation)  
- [Environment Configuration](#environment-configuration)  
- [Usage](#usage-in-laravel)  
- [Methods](#methods-overview)  
- [FAQs](#faqs)  
- [Contributing](#contributing)  
- [Security Vulnerabilities](#security-vulnerabilities)    
- [License](#license)  
- [Contributing](#contributing)  
- [Support](#get-support)   


## Getting Started with Huntglitch  

Before implementing the package, please complete these setup steps:  

1. **Register an Account**  
   Sign up for a free Huntglitch account here: [https://app.huntglitch.com/register](https://app.huntglitch.com/register)  

2. **Create a Project**  
   Once registered, create a new project here: [https://app.huntglitch.com/project/create](https://app.huntglitch.com/project/create)  

3. **Follow the Installation & Implementation Guide**  
   Implement this package in your Laravel application by following the steps in the [Installation](#installation) and [Usage in Laravel](#usage-in-laravel) sections of this README.  

4. **View Your Logs & Error Reports**  
   After the integration is complete and your application starts generating logs, you can view all errors, warnings, and queries in your Huntglitch dashboard:  
   [https://app.huntglitch.com/projects](https://app.huntglitch.com/projects) → Select your project to view detailed logs.  


## Features

- Automatic logging of exceptions and errors  
- Track and store MySQL query logs  
- Integrate easily with Laravel exception handler  
- PSR-4 autoloading support  
- Laravel vendor publish support for easy config  
- Built-in support for tagging logs with severity levels  


## Supported Versions

- **PHP**: ^8.0  
- **Laravel**: ^9.0 | ^10.0 | ^11.0 | ^12.0

## Installation

Run the following command to install the package via Composer:

```bash
composer require itpathsolutions/huntglitch
```

## Environment Variables

Add these variables to your `.env` file if you are using Huntglitch integration:

```env
HUNTGLITCH_PROJECT_ID={your-project-id}
HUNTGLITCH_DELIVERABLE_ID={your-deliverable-id}
HUNTGLITCH_LOG_ENDPOINT=https://api.huntglitch.com/
```

And update `config/app.php` to use them:

```php
'HUNTGLITCH_PROJECT_ID' => env('HUNTGLITCH_PROJECT_ID', ''),
'HUNTGLITCH_DELIVERABLE_ID' => env('HUNTGLITCH_DELIVERABLE_ID', ''),
'HUNTGLITCH_LOG_ENDPOINT' => env('https://api.huntglitch.com/', ''),
```

Finally, regenerate the autoloader:

```bash
composer dump-autoload
```


## Usage in Laravel

### ➤ Basic Usage in a try-catch block

```php
use Itpath\Huntglitch\Huntglitch;

try {
    echo 100 / 0;
} catch (Throwable $e) {
    $glitch = new Huntglitch();
    $glitch->add($e);
}
```

### ➤ Global Usage for Exception Logging

#### **For Laravel 9, 10, and 11** (`Handler.php` method)  

Update the `register()` method in `app/Exceptions/Handler.php`:

```php
use Itpath\Huntglitch\Huntglitch;

public function register()
{
    $this->reportable(function (Throwable $e) {
        $glitch = new Huntglitch();
        $glitch->add($e);
    });
}
```
#### **For Laravel 12** (`bootstrap/app.php` method)  

In Laravel 12, the `Handler.php` file is no longer used for exception handling.  
Instead, open `bootstrap/app.php` and add the following inside the **exception configuration** section:  

```php
use Illuminate\Foundation\Configuration\Exceptions;
use Itpath\Huntglitch\Huntglitch;

->withExceptions(function (Exceptions $exceptions): void {
    $exceptions->report(function (\Throwable $e) {
        $glitch = new Huntglitch();
        $glitch->add($e);
    });
})
->create();
```

## Methods Overview

### `add($exception, $type = 'error', $additionalData = [])`

- Logs a message or exception
- Optional second parameter sets severity (e.g., `debug`, `info`, `warning`, `error`)
- Third optional parameter is an array for custom context data

### Predefined Logging Methods

You can also use:

```php
addError($e, $data);
addWarning($e, $data);
addInfo($e, $data);
addDebug($e, $data);
addNotice($e, $data);
```

All these methods accept:
- `string|Throwable $e`
- `array $data (optional)`

## **FAQs**  

### 1. What does this package do?  
The **Huntglitch** package provides an **Error & Query Logger Package** to monitor **errors, warning, info and debug** of Laravel applications.  

### 2. How do I install the package?  
Run the following command in your terminal:  
```bash
composer require itpathsolutions/huntglitch
```  

### 3. Which Laravel versions are supported?  
This package supports **Laravel 9, 10, 11, and 12** with **PHP 8+** compatibility.   


### 4. How do I update the package to the latest version?  
Run the following command to update:  
```bash
composer update itpathsolutions/huntglitch
```  

### 5. Can I contribute to this package?  
Absolutely! Contributions are welcome. See the [CONTRIBUTING](https://github.com/vidhi-nirmal71/huntglitch/blob/main/CONTRIBUTING.md) guidelines for details.  

### 6. Where can I get support?  
For any support or queries, contact us via [IT Path Solutions](https://www.itpathsolutions.com/contact-us/).  

## **Contributing**  
We welcome contributions from the community! Feel free to **Fork** the repository and contribute to this module. You can also create a pull request, and we will merge your changes into the main branch. See [CONTRIBUTING](https://github.com/vidhi-nirmal71/huntglitch/blob/main/CONTRIBUTING.md) for details.  

## **Security Vulnerabilities**  
Please review our [Security Policy](https://github.com/vidhi-nirmal71/huntglitch/security/policy) on how to report security vulnerabilities.  

## **License**  
This package is open-source and available under the MIT License. See the [LICENSE](https://github.com/vidhi-nirmal71/huntglitch/blob/main/LICENSE) file for details.  

## **Get Support**  
- Feel free to [contact us](https://www.itpathsolutions.com/contact-us/) if you have any questions.  
- If you find this project helpful, please give us a ⭐ [Star](https://github.com/vidhi-nirmal71/huntglitch/stargazers).  

## **You may also find our other useful packages:**  
- [MySQL Info Package](https://packagist.org/packages/itpathsolutions/mysqlinfo)  
- [PHP Info Package](https://packagist.org/packages/itpathsolutions/phpinfo)  
- [Role Wise Session Manager Package](https://packagist.org/packages/itpathsolutions/role-wise-session-manager)  
- [Authinfo - User Login Tracker](https://packagist.org/packages/itpathsolutions/authinfo)   
- [Chatbot Package](https://packagist.org/packages/itpathsolutions/chatbot)   
- [Redis Package](https://packagist.org/packages/itpathsolutions/redisinfo)   
