Got it! Here's a **properly formatted README.md** file based on the info you gave me, with clear sections and clean Markdown styling.

---

````markdown
# Swati_Textile_Erp

## Environment Setup

- **Node.js**: 20
- **PHP**: 8.3
- **Laravel**: 12

---

## Generate API Documentation

Run the following command to generate API docs using Swagger:

```bash
php artisan l5-swagger:generate
````

Access API documentation at:

```
http://127.0.0.1:8000/api/documentation#/Users
```

---

## Database Seeding

Seed the database with initial data by running:

```bash
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=StockSeeder
php artisan db:seed --class=OrderSeeder
php artisan db:seed --class=JobsSeeder
```

---

## Laravel Passport Setup

Create a personal access client and generate keys for Laravel Passport:

```bash
php artisan passport:client --personal
php artisan passport:keys
```

---

## License

MIT License

Copyright (c) 2025 Dileep

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:
