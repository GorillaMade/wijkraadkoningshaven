document.addEventListener('DOMContentLoaded', function () {
  const itemlist = document.querySelector('.contentitems');
  const loadmoreBtn = document.getElementById('loadmoreposts');
  const checkboxes = document.querySelectorAll('.filter-checkbox');

  if (!itemlist || !loadmoreBtn) return;

  const batchSize = 6;

  function getVisibleItems() {
    return Array.from(itemlist.querySelectorAll('li')).filter(li => !li.classList.contains('hidden'));
  }

  function hideAllPosts() {
    itemlist.querySelectorAll('li').forEach(li => li.style.display = 'none');
  }

  function loadMorePosts() {
    const items = getVisibleItems();
    const currentlyVisible = items.filter(li => li.style.display === 'block');
    const nextBatch = items.slice(currentlyVisible.length, currentlyVisible.length + batchSize);

    nextBatch.forEach(li => {
      const img = li.querySelector('img[data-src]');
      if (img && !img.src) img.src = img.dataset.src;
      li.style.display = 'block';
    });

    // Knop verbergen als alles zichtbaar
    if (currentlyVisible.length + nextBatch.length >= items.length) {
      loadmoreBtn.style.display = 'none';
    } else {
      loadmoreBtn.style.display = 'inline-block';
    }
  }

  function updatePostFilter() {
    hideAllPosts();

    const selected = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);

    itemlist.querySelectorAll('li').forEach(li => {
      li.classList.remove('hidden');
      if (selected.length && !selected.some(tag => li.classList.contains('tag_' + tag))) {
        li.classList.add('hidden');
      }
    });

    loadMorePosts();
  }

  checkboxes.forEach(cb => cb.addEventListener('change', updatePostFilter));
  loadmoreBtn.addEventListener('click', loadMorePosts);

  // Initial load
  updatePostFilter();
});
