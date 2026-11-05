# Products API

Base URL: `/api/products.php`

All requests require:
`Authorization: Bearer YOUR_CONFIGURED_API_TOKEN`

- `GET /api/products.php` – list products
- `GET /api/products.php?id=1` – read one product
- `POST /api/products.php` – JSON body `{ "name": "Pen", "price": 20 }`
- `PUT /api/products.php?id=1` – JSON body `{ "name": "Blue Pen", "price": 25 }`
- `DELETE /api/products.php?id=1` – delete product

For a real deployment, store the token outside source control and do not embed a long-lived secret in browser JavaScript. A browser-facing application should normally use user sessions or short-lived scoped tokens issued by a backend.
