# Price API

Endpoint: `GET /api/price`

Query parameters:
- `product_id` (integer, required) — id du produit
- `qty` (integer, required) — quantité demandée

Response:
- JSON `{ "price": number | null }` — prix unitaire applicable ou `null` si aucune règle ne correspond

Examples

Curl:

```bash
curl "http://localhost/api/price?product_id=1&qty=10"
# => { "price": 9.5 }
```

JavaScript (fetch):

```js
import { getPrice } from '/js/price.js';

async function example() {
  const price = await getPrice(1, 10);
  console.log('Price:', price);
}

example();
```

Filament integration (suggestion):
- Add a small Livewire / Alpine snippet in the Product edit page that listens to a `qty` input and calls `/api/price` to show a live price preview. See `public/js/price.js` for a minimal client helper.
