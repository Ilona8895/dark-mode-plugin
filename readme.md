# Dark Mode Plugin

A WordPress plugin that adds a dark mode toggle with customizable colors, designed for the [Roślinopedia](https://roslinopedia.pl/) theme.

## Features

- **Dark mode toggle** — floating button to switch between light and dark themes
- **Persistent preference** — user choice saved in `localStorage`
- **Customizable colors** — 9 color slots (primary, secondary, accent, background, text, link, etc.)
- **Toggle position** — bottom-left or bottom-right
- **Default state** — dark mode can be on or off by default for new visitors
- **CSS variables** — overrides theme variables (`--color-*`) when dark mode is active

## Requirements

- WordPress 6.0+
- PHP 8.0+
- [Roślinopedia Theme](https://github.com/Ilona8895/roslinopedia-theme) (recommended) — uses matching CSS variables
- Font Awesome (loaded by the theme)

## Installation

1. Clone or download the plugin to `wp-content/plugins/dark-mode-plugin`:

   ```bash
   git clone https://github.com/Ilona8895/dark-mode-plugin.git wp-content/plugins/dark-mode-plugin
   ```

2. Activate the plugin in WordPress admin: **Plugins → Installed Plugins**.

3. Configure settings: **Dark Mode** in the admin sidebar.

## Configuration

Go to **Dark Mode** in the WordPress admin to:

- **Default Dark Mode** — enable to show dark mode by default for new visitors
- **Toggle Position** — choose bottom-left or bottom-right
- **Color slots** — customize each color when using custom palette (disable "Default Dark Mode" to unlock color pickers)

### Color slots

| Slot           | Default   | Description            |
| -------------- | --------- | ---------------------- |
| Primary        | `#1f4d3a` | Primary brand color    |
| Secondary      | `#2f6b4f` | Secondary color        |
| Accent         | `#c8a96a` | Accent / toggle button |
| Background     | `#0f1110` | Main background        |
| Background Alt | `#161a17` | Alternate background   |
| Text           | `#e8efe9` | Main text color        |
| Text Muted     | `#9aa39c` | Muted text             |
| Link           | `#6fbf8a` | Link color             |
| Border         | `#2a2f2b` | Border color           |

## How it works

- When dark mode is active, the plugin injects CSS variables that override the theme's `--color-*` variables.
- The theme must use these variables for colors to support dark mode.
- User preference is stored in `localStorage` under the key `roslinopedia-dark-mode`.

## Related

- [Roślinopedia Theme](https://github.com/Ilona8895/roslinopedia-theme) — the theme this plugin is designed for
- [Roslinopedia.pl](https://roslinopedia.pl/) — see it in action

## Author

Ilona Melcher
