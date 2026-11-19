const menuButton = document.querySelector('.menu-toggle');
const nav = document.querySelector('.primary-nav');

function closeMenu() {
  menuButton.setAttribute('aria-expanded', 'false');
  menuButton.setAttribute('aria-label', 'Abrir menú');
  nav.classList.remove('is-open');
}

menuButton.addEventListener('click', () => {
  const isOpen = menuButton.getAttribute('aria-expanded') === 'true';
  menuButton.setAttribute('aria-expanded', String(!isOpen));
  menuButton.setAttribute('aria-label', isOpen ? 'Abrir menú' : 'Cerrar menú');
  nav.classList.toggle('is-open', !isOpen);
});

nav.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));
document.addEventListener('keydown', (event) => {
  if (event.key === 'Escape') closeMenu();
});

document.getElementById('year').textContent = new Date().getFullYear();

const revealElements = document.querySelectorAll('[data-reveal]');
if ('IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.08, rootMargin: '0px 0px -20px 0px' });
  revealElements.forEach((element) => observer.observe(element));
} else {
  revealElements.forEach((element) => element.classList.add('is-visible'));
}

const galleryTrack = document.querySelector('.gallery-track');
const galleryPhotos = [...document.querySelectorAll('.gallery-photo')];
const galleryDialog = document.querySelector('.gallery-dialog');
const galleryDialogImage = galleryDialog.querySelector('.gallery-dialog-image');
let activePhoto = 0;

document.querySelectorAll('[data-gallery-scroll]').forEach((button) => {
  button.addEventListener('click', () => {
    const firstPhoto = galleryPhotos[0];
    const gap = parseFloat(getComputedStyle(galleryTrack).columnGap) || 0;
    const step = firstPhoto.getBoundingClientRect().width + gap;
    const direction = button.dataset.galleryScroll === 'next' ? 1 : -1;
    galleryTrack.scrollBy({ left: step * direction, behavior: 'smooth' });
  });
});

function showGalleryPhoto(index) {
  activePhoto = (index + galleryPhotos.length) % galleryPhotos.length;
  const image = galleryPhotos[activePhoto].querySelector('img');
  galleryDialogImage.src = image.src;
  galleryDialogImage.alt = image.alt;
}

galleryPhotos.forEach((photo, index) => {
  photo.addEventListener('click', () => {
    showGalleryPhoto(index);
    galleryDialog.showModal();
  });
});

galleryDialog.querySelector('.gallery-close').addEventListener('click', () => galleryDialog.close());
galleryDialog.querySelector('.gallery-dialog-prev').addEventListener('click', () => showGalleryPhoto(activePhoto - 1));
galleryDialog.querySelector('.gallery-dialog-next').addEventListener('click', () => showGalleryPhoto(activePhoto + 1));
galleryDialog.addEventListener('click', (event) => {
  if (event.target === galleryDialog) galleryDialog.close();
});
galleryDialog.addEventListener('keydown', (event) => {
  if (event.key === 'ArrowLeft') showGalleryPhoto(activePhoto - 1);
  if (event.key === 'ArrowRight') showGalleryPhoto(activePhoto + 1);
});
