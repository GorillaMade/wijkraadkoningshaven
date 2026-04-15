document.addEventListener('DOMContentLoaded', function () {
  const itemlist = document.querySelector('.contentitems');
  const loadmoreBtn = document.getElementById('loadmoreposts');
  const checkboxes = document.querySelectorAll('.filter-checkbox');

  const pagesize = parseInt(itemlist?.dataset.pagesize || '9', 10);

  if (!itemlist || !checkboxes.length) return;

  function hideAllPosts() {
    itemlist.querySelectorAll('li').forEach(li => li.style.display = 'none');
  }

  function loadMorePosts() {
    const items = Array.from(itemlist.querySelectorAll('li:not(.hidden)'));

    // welke items zijn al zichtbaar?
    const alreadyVisible = items.filter(li => li.style.display === 'block');

    // pak de volgende batch
    const nextItems = items.slice(
      alreadyVisible.length,
      alreadyVisible.length + pagesize
    );

    nextItems.forEach(li => {
      const img = li.querySelector('img[data-src]');
      if (img && !img.src) img.src = img.dataset.src;
      li.style.display = 'block';
    });

    // Update zichtbaarheid load more knop
    if (loadmoreBtn) {
      if (alreadyVisible.length + nextItems.length >= items.length) {
        loadmoreBtn.style.display = 'none';
      } else {
        loadmoreBtn.style.display = 'inline-block';
      }
    }
  }

  function updatePostFilter() {
    hideAllPosts();

    const selected = Array.from(checkboxes)
      .filter(cb => cb.checked)
      .map(cb => cb.value);

    const allItems = itemlist.querySelectorAll('li');
    allItems.forEach(li => li.classList.remove('hidden'));

    // filteren
    if (selected.length) {
      allItems.forEach(li => {
        const hasTag = selected.some(tag => li.classList.contains('tag_' + tag));
        if (!hasTag) li.classList.add('hidden');
      });
    }

    // toon eerste batch
    loadMorePosts();
  }

  // events
  checkboxes.forEach(cb => cb.addEventListener('change', updatePostFilter));
  if (loadmoreBtn) loadmoreBtn.addEventListener('click', loadMorePosts);

  // initial load
  updatePostFilter();
});
