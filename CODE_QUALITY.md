# G-code Reference - Code Quality Setup

## Installed Tools

### 1. PHPCS (PHP_CodeSniffer)
WordPress Coding Standards compliance checker.

**Installation:**
```bash
composer require --dev squizlabs/php_codesniffer
composer require --dev wp-coding-standards/wpcs
phpcs --config-set installed_paths vendor/wp-coding-standards/wpcs
```

**Usage:**
```bash
npm run lint:php          # Check PHP code
npm run format:php        # Auto-fix PHP issues
```

**Configuration:** `phpcs.xml`

---

### 2. ESLint
JavaScript linter with WordPress coding standards.

**Installation:**
```bash
npm install
```

**Usage:**
```bash
npm run lint:js           # Check JavaScript
npm run format:js         # Auto-fix JavaScript issues
```

**Configuration:** `.eslintrc.json`

---

### 3. Combined Linting

Run all linters at once:
```bash
npm run lint
```

---

## WordPress.org Requirements

✅ **PHPCS** is required for WordPress.org plugin submission  
✅ **ESLint** is recommended for quality JavaScript  
✅ Configuration follows WordPress Coding Standards

---

## Pre-Commit Hook (Optional)

Add to `.git/hooks/pre-commit`:
```bash
#!/bin/sh
npm run lint
if [ $? -ne 0 ]; then
  echo "Linting failed. Commit aborted."
  exit 1
fi
```

---

## CI/CD Integration

### GitHub Actions Example:
```yaml
name: Code Quality
on: [push, pull_request]
jobs:
  lint:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '7.4'
      - name: Install Composer deps
        run: composer install
      - name: PHPCS
        run: npm run lint:php
      - name: ESLint  
        run: npm run lint:js
```

---

_Setup complete! Run `npm run lint` to check code quality._
