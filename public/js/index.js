const menuBtn = document.querySelector('.menu-btn');
const navMenu = document.querySelector('.nav-menu');
const index = document.querySelector('.index-container');
let navMenuStatus = false;

menuBtn.addEventListener('click', () => {
  if (!navMenuStatus) {
    navMenu.style.transform = 'scaley(100%)';
    navMenu.style.transition = 'transform .2s';
    index.style.bottom = "calc((1.53rem * (-5)) - (2px * 5) - (.6rem * 10))";
    navMenuStatus = !navMenuStatus;
  } else {
    navMenu.style.transform = 'scaley(0%)';
    navMenu.style.transition = 'transform .2s';
    index.style.bottom = '0';
    navMenuStatus = !navMenuStatus;
  }
});

function changeBackground(index, el, target, event, color) {
  el[index].addEventListener(event, () => {
    target[index].style.backgroundColor = color;
  })
}

function changeUnderline(index, el, target, event, style) {
  el[index].addEventListener(event, () => {
    target[index].style.textDecoration = style;
  })
}

const posts = document.querySelectorAll('.post-container');
const titles = document.querySelectorAll('.title');
const continueLinks = document.querySelectorAll('.continue');

for (let index = 0; index < posts.length; index++) {
  changeBackground(index, continueLinks, posts, 'mouseover', 'var(--panel-h)');
  changeBackground(index, continueLinks, posts, 'mouseout', 'transparent');
  changeUnderline(index, continueLinks, titles, 'mouseover', 'underline 2px');
  changeUnderline(index, continueLinks, titles, 'mouseout', 'none');
  changeUnderline(index, continueLinks, continueLinks, 'mouseover', 'underline');
  changeUnderline(index, continueLinks, continueLinks, 'mouseout', 'none');
  changeBackground(index, posts, posts, 'mouseover', 'var(--panel-h)');
  changeBackground(index, posts, posts, 'mouseout', 'transparent');
  changeUnderline(index, posts, titles, 'mouseover', 'underline 2px');
  changeUnderline(index, posts, titles, 'mouseout', 'none');
  changeUnderline(index, posts, continueLinks, 'mouseover', 'underline');
  changeUnderline(index, posts, continueLinks, 'mouseout', 'none');
}