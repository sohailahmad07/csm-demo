# CSM Demo

A client success manager demo application built with the Laravel ecosystem. Tracks agency onboarding progress, payment
collection, and CSM activity across multiple clients.

## Features

- **Dashboard** — KPI overview (total clients, at-risk, onboarding, total collected), monthly collections bar chart, and
  at-risk/onboarding client panels
- **Client List** — Searchable, filterable table with status tabs (All, Onboarding, Active, At Risk), lazy-loaded
  islands, and loading placeholders
- **Client Detail** — Onboarding checklist with drag-to-reorder, due dates, overdue tracking, progress bars, CSM notes,
  recent payments, and activity timeline
- **Create Client** — Two-step form with a dynamic onboarding step builder, group management (
  add/rename/delete/reorder), and per-step due dates

## Stack

| Layer             | Package         | Version |
|-------------------|-----------------|---------|
| Framework         | Laravel         | 12      |
| Reactive UI       | Livewire        | 4       |
| Component Library | Flux UI Free    | 2       |
| Styling           | Tailwind CSS    | 4       |
| Auth Backend      | Laravel Fortify | 1       |
| Testing           | Pest            | 4       |
| Database          | SQLite          | —       |
| Runtime           | PHP             | 8.4     |

## Getting Started

Make sure you have [Composer](https://getcomposer.org/) installed,
and have installed PHP 8.4 or higher. and node 18.0 or higher.

```bash
- composer setup
- php artisan migrate --seed
```

## Seed Data

The seeder creates 6 agencies across three states:

- **Active** — fully onboarded with 30–80 payments/month
- **At Risk** — partial onboarding, low payment volume (5–15/month)
- **Onboarding** — in setup, no payments yet

## Testing

```bash
php artisan test --compact
```
