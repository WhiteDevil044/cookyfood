const menuToggle = document.querySelector('.menu-toggle');
const menuList = document.querySelector('.menu-list');
const body = document.body;

menuToggle.addEventListener('click', (e) => {
    e.stopPropagation();
    menuList.classList.toggle('active');
    body.classList.toggle('menu-open');
});

document.addEventListener('click', (e) => {
    if (!e.target.closest('nav') && window.innerWidth <= 768) {
        menuList.classList.remove('active');
        body.classList.remove('menu-open');
    }
});

window.addEventListener('resize', () => {
    if (window.innerWidth > 768) {
        menuList.classList.remove('active');
        body.classList.remove('menu-open');
    }
});

window.addEventListener('scroll', () => {
    if (window.innerWidth <= 768 && menuList.classList.contains('active')) {
        menuList.classList.remove('active');
        body.classList.remove('menu-open');
    }
});