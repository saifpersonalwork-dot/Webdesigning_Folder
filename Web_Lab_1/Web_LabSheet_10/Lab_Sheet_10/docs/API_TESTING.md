# API testing

Register a user in the app. During registration, the app generates a random API token.
For this lab demo, the raw token is placed in the PHP session as `new_api_token`; inspect
it during local development or add a temporary display after registration, then remove
that display. Only its password hash is stored in the database.

Example request (replace TOKEN):
```bash
curl -H "Authorization: Bearer TOKEN" "http://localhost/Web_Lab_Solutions/public/index.php?route=api/products"
curl -X POST -H "Authorization: Bearer TOKEN" -H "Content-Type: application/json" \
  -d '{"name":"Pen","category":"Stationery","price":10}' \
  "http://localhost/Web_Lab_Solutions/public/index.php?route=api/products"
curl -X PUT -H "Authorization: Bearer TOKEN" -H "Content-Type: application/json" \
  -d '{"id":1,"name":"Blue Pen","category":"Stationery","price":12}' \
  "http://localhost/Web_Lab_Solutions/public/index.php?route=api/products"
curl -X DELETE -H "Authorization: Bearer TOKEN" \
  "http://localhost/Web_Lab_Solutions/public/index.php?route=api/products&id=1"
```
