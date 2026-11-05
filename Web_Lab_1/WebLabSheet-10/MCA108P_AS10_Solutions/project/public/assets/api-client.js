const API_URL = '/api/products.php';
const API_TOKEN = 'REPLACE_WITH_CONFIGURED_TOKEN';

async function loadProducts() {
  const response = await fetch(API_URL, {
    headers: { Authorization: `Bearer ${API_TOKEN}` }
  });
  if (!response.ok) throw new Error(`API error: ${response.status}`);
  return response.json();
}

async function createProduct(name, price) {
  const response = await fetch(API_URL, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${API_TOKEN}`
    },
    body: JSON.stringify({ name, price })
  });
  if (!response.ok) throw new Error(`API error: ${response.status}`);
  return response.json();
}

// Example:
// loadProducts().then(console.log).catch(console.error);
