# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a **Moodle 4.5.4+ LMS** installation with a custom theme development environment. The current working directory is `/theme/massey/` - a custom theme for the Massey institution that is currently empty and ready for development.

## Key Development Commands

**From the Moodle root directory (`/home/arowatt/moodle405/`)**:

```bash
# Build CSS from SCSS
grunt css

# Build JavaScript/AMD modules  
grunt js
grunt amd

# Lint code
grunt eslint    # JavaScript
grunt stylelint # CSS/SCSS

# Watch for changes during development
grunt watch

# Run all linting
grunt eslint stylelint

# Create upgrade notes
npm run upgradenote
```

**Requirements**: Node.js 22.11.0+, Grunt CLI installed globally

## Theme Development Structure

Moodle themes follow this structure:
```
theme/massey/
├── config.php          # Theme configuration & inheritance
├── version.php          # Version info & dependencies  
├── lib.php             # Theme functions & callbacks
├── settings.php        # Admin setting definitions
├── lang/en/            # Language strings
├── layout/             # Page layout templates (PHP)
├── templates/          # Mustache templates  
├── scss/               # Sass stylesheets
├── amd/src/           # AMD JavaScript modules
└── classes/           # PHP classes & renderers
```

## Architecture Overview

**Theme Inheritance**: Moodle themes inherit from parent themes (usually `boost` which uses Bootstrap 4/5). The `config.php` file defines parent relationships and overrides.

**Build System**: 
- SCSS compilation happens at the Moodle root level via Grunt
- AMD JavaScript modules are processed and minified
- Templates are compiled and cached
- All themes share the same build pipeline

**Template System**: Uses Mustache templating with PHP context objects. Templates in `templates/` override core/parent templates by filename matching.

**JavaScript**: Uses AMD module system. Files in `amd/src/` are built to `amd/build/` with dependency resolution.

**Styling**: SCSS files in `scss/` are compiled. Themes typically override Bootstrap variables and add custom styles.

## Development Workflow

1. **Theme Creation**: Start with `config.php` and `version.php` to define the theme
2. **Inheritance**: Usually inherit from `boost` theme for Bootstrap base
3. **Styling**: Override SCSS variables and add custom styles in `scss/`
4. **Templates**: Override specific Mustache templates in `templates/`
5. **Build**: Run `grunt css` and `grunt js` from Moodle root after changes
6. **Testing**: Test across different page types (course, dashboard, login, etc.)

## Important Notes

- **Working Directory**: All grunt commands must be run from `/home/arowatt/moodle405/` (Moodle root), not from the theme directory
- **Cache**: Moodle caches compiled themes. You may need to purge caches during development
- **Bootstrap**: Most themes inherit Bootstrap 4/5 from the `boost` theme
- **Mobile**: Themes must be responsive and work across all device types
- **Accessibility**: Must maintain WCAG compliance standards
- **Multi-language**: Support multiple languages via `lang/` directories

## File Patterns

- **Theme config**: `theme/*/config.php` 
- **SCSS sources**: `theme/*/scss/**/*.scss`
- **AMD sources**: `theme/*/amd/src/**/*.js`
- **Templates**: `theme/*/templates/**/*.mustache`
- **Language files**: `theme/*/lang/en/*.php`

## Testing & Quality

Run linting before committing:
```bash
grunt eslint stylelint
```

The codebase enforces:
- ESLint for JavaScript (ES6+ with AMD modules)
- Stylelint for CSS/SCSS  
- Modern browser support (no IE/Opera support)
- Node.js 22.11.0+ requirement