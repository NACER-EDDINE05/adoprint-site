export async function getPrice(productId, qty) {
    const url = `/api/price?product_id=${encodeURIComponent(productId)}&qty=${encodeURIComponent(qty)}`;

    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
    if (!res.ok) return null;

    const json = await res.json();
    return json.price ?? null;
}

// Example usage in plain JS:
// getPrice(1, 10).then(p => console.log('price', p));
