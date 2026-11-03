document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('liveSearch');
  const box = document.getElementById('suggestions');
  if (!input || !box) return;
  let timer;
  input.addEventListener('input', () => {
    clearTimeout(timer);
    timer = setTimeout(async () => {
      const response = await fetch(`index.php?route=products/suggest&q=${encodeURIComponent(input.value)}`);
      if (!response.ok) return;
      const rows = await response.json();
      box.replaceChildren();
      rows.forEach(row => {
        const p = document.createElement('p');
        p.textContent = `${row.name} — ₹${row.price}`;
        box.appendChild(p);
      });
    }, 250);
  });
});
