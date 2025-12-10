# Contributing to Filament Activity Logs

Thank you for considering contributing to Filament Activity Logs! We welcome contributions from the community.

## Code of Conduct

Please be respectful and constructive in all interactions.

## How to Contribute

### Reporting Bugs

If you find a bug, please create an issue with:

- A clear, descriptive title
- Steps to reproduce the issue
- Expected behavior
- Actual behavior
- Your environment (PHP version, Laravel version, Filament version)

### Suggesting Features

Feature requests are welcome! Please create an issue with:

- A clear description of the feature
- Use cases and benefits
- Any implementation ideas you might have

### Pull Requests

1. **Fork the repository** and create your branch from `main`
2. **Install dependencies**: `composer install`
3. **Make your changes** following our coding standards
4. **Add tests** for new functionality
5. **Run tests**: `composer test`
6. **Run linting**: `composer format`
7. **Commit your changes** with clear, descriptive commit messages
8. **Push to your fork** and submit a pull request

## Development Setup

```bash
# Clone your fork
git clone https://github.com/YOUR-USERNAME/filament-activity-logs.git
cd filament-activity-logs

# Install dependencies
composer install

# Run tests
composer test

# Run code formatting
composer format
```

## Coding Standards

- Follow **PSR-12** coding standards
- Use **Laravel Pint** for code formatting (run `composer format`)
- Write **clear, descriptive variable and method names**
- Add **PHPDoc blocks** for classes and methods
- Write **tests** for new features

## Testing

- All new features must include tests
- Tests should be written using **Pest PHP**
- Ensure all tests pass before submitting a PR
- Aim for high test coverage

## Commit Messages

- Use clear, descriptive commit messages
- Start with a verb in present tense (e.g., "Add", "Fix", "Update")
- Reference issue numbers when applicable

## Questions?

Feel free to open an issue for any questions or discussions!

## License

By contributing, you agree that your contributions will be licensed under the MIT License.
