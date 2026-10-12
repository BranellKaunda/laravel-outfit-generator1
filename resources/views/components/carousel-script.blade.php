<script>
  document.querySelectorAll('[data-carousel]').forEach((carousel) => {
    if (carousel.dataset.carousel === 'initialized') {
      return;
    }

    carousel.dataset.carousel = 'initialized';

    const items = carousel.querySelectorAll('[data-carousel-item]');
    const prev = carousel.querySelector('[data-carousel-prev]');
    const next = carousel.querySelector('[data-carousel-next]');

    if (items.length < 2) {
      return;
    }

    let current = 0;

    const show = (index) => {
      items.forEach((image, i) => image.classList.toggle('hidden', i !== index));
    };

    prev.addEventListener('click', () => {
      current = (current - 1 + items.length) % items.length;
      show(current);
    });

    next.addEventListener('click', () => {
      current = (current + 1) % items.length;
      show(current);
    });
  });
</script>