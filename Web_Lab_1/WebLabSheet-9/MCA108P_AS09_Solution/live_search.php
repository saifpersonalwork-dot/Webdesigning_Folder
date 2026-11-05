<?php require_once __DIR__.'/includes/helpers.php'; require_login(); ?>
<h2>Live Product Search (AJAX)</h2>
<input id="search" type="search" placeholder="Type product name">
<div id="suggestions"></div>
<script>
const field = document.getElementById('search');
const output = document.getElementById('suggestions');
field.addEventListener('input', async () => {
  const q = field.value.trim();
  if (!q) { output.textContent = ''; return; }
  try {
    const response = await fetch('search_suggestions.php?q=' + encodeURIComponent(q));
    if (!response.ok) throw new Error('Search failed');
    const data = await response.json();
    output.replaceChildren();
    data.forEach(item => {
      const row = document.createElement('div');
      row.textContent = item.name + ' — ₹' + item.price;
      output.appendChild(row);
    });
  } catch (error) { output.textContent = 'Unable to load suggestions.'; }
});
</script>
