const menuContainer = document.querySelector('#menuContainer');
const menuLink = document.querySelector('#menuContainerLinkAdditional4');
const mediaQuery490 = window.matchMedia('(max-width: 490px)');
const mediaQuery390 = window.matchMedia('(max-width: 390px)');
let isOpened = false;


menuLink.addEventListener('mouseenter', () => {
    isOpened = true;
    if (mediaQuery390.matches) {
        menuContainer.style.height = '140px';
    }
    else if (mediaQuery490.matches) {
        menuContainer.style.height = '200px';
    }
    else {
        menuContainer.style.height = '250px';
    }
})

menuLink.addEventListener('mouseleave', () => {
    isOpened = false;
    if (mediaQuery490.matches || mediaQuery390.matches) {
        menuContainer.style.height = '50px';
    }
    else {
        menuContainer.style.height = '70px';
    }
})

mediaQuery490.addEventListener('change', () => {
    if (mediaQuery490.matches && isOpened){
        menuContainer.style.height = '200px';
    }
    else if (isOpened){
        menuContainer.style.height = '250px';
    }
});

mediaQuery390.addEventListener('change', () => {
    if (mediaQuery390.matches && isOpened){
        menuContainer.style.height = '140px';
    }
    else if (isOpened){
        menuContainer.style.height = '200px';
    }
})