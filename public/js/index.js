// Index post menu
const indexMenus = document.querySelectorAll('.dropdown');
const indexArrows = document.querySelectorAll('.post > .menu > .arrow');

indexArrows.forEach((arrow, index) => {
  let menuShow = false;
  arrow.addEventListener('click', () => {
    if (menuShow == false) {
      indexMenus[index].style.transform = 'scaley(100%)';
      arrow.style.transform = 'rotate(90deg)';
      menuShow = true;
    } else {
      indexMenus[index].style.transform = 'scaley(0%)';
      arrow.style.transform = 'rotate(0deg)';
      menuShow = false;
    }
  })
});

const navMenu = document.querySelector('.nav-menu');
const menuBtn = document.querySelector('.menu-btn');
const sidebar = document.querySelector('.sidebar')
const sidebarBtn = document.querySelector('.sidebar-btn');
const body = document.querySelector('.body-container');

let navMenuStatus = false;
let sidebarStatus = false;

menuBtn.addEventListener('click', () => {
  if (!navMenuStatus) {
    navMenu.style.transform = 'scaley(100%)';
    body.style.bottom = "calc((1.53rem * (-5)) - (2px * 5) - (.6rem * 10))";
    sidebar.style.bottom = "calc((-4.8rem - 4px) + (1.53rem * (-5)) - (2px * 5) - (.6rem * 10))";
    navMenuStatus = !navMenuStatus;
  } else {
    navMenu.style.transform = 'scaley(0%)';
    sidebar.style.bottom = 'calc(-4.8rem - 4px)';
    body.style.bottom = '0';
    navMenuStatus = !navMenuStatus;
  }
});

sidebarBtn.addEventListener('click', () => {
  if (!sidebarStatus) {
    sidebar.style.transform = 'scalex(100%)';
    body.style.transform = "translateX(calc(-80% + -4px))";
    sidebarStatus = !sidebarStatus;
  } else {
    sidebar.style.transform = 'scalex(0%)';
    body.style.transform = 'translateX(0)';
    sidebarStatus = !sidebarStatus;
  }
})

const changeBackground = (index, el, target, event, color) => {
  el[index].addEventListener(event, () => {
    target[index].style.backgroundColor = color;
  })
}

const changeUnderline = (index, el, target, event, style) => {
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

// Replace text field value on file upload
const fileText = document.querySelector("#thumb");
const child = document.querySelector(".file-up-field");

// Get uploaded file name and write it to the value attribute
const getFileData = () => {
  const file = fileText.files[0];

  child.setAttribute('value', file.name);
}

if (fileText) {
  child.addEventListener('keydown', (event) => {
    event.preventDefault();
  })
  child.addEventListener('paste', (event) => {
    event.preventDefault();
  })
  child.addEventListener('focus', (event) => {
    event.preventDefault();
  })
  child.addEventListener('mousedown', (event) => {
    event.preventDefault();
  })

  fileText.addEventListener('change', getFileData);
}

const popupContainer = document.querySelector('.popup-container');
const popup = document.querySelector('.popup');

if (popup && popup.textContent) {
  setTimeout(() => {
    popupContainer.style.top = '90%';
  }, 50);
  setTimeout(() => {
    popupContainer.style.top = '110%';
  }, 2300);
}

// Live convert description textarea content to Markdown and send it to preview window
const inputTab = document.querySelector(".input-tab");
if (inputTab) {
  const descriptionTab = document.querySelector(".description-tab");
  const target = document.getElementById('body-preview');
  const inputDiv = document.getElementById('body').parentElement;
  const dislayDiv = target.parentElement;
  const converter = new showdown.Converter();

  converter.setFlavor('github');

  let inputs = true;
  let previews = false;

  inputTab.addEventListener('click', () => {
    if (inputs == false) {
        inputTab.classList.add("active");
        inputDiv.classList.add("active");

        descriptionTab.classList.remove("active");
        dislayDiv.classList.remove("active");

        inputs = true;
        previews = false;
    }
  });

  descriptionTab.addEventListener('click', () => {
    if (previews == false) {
      inputTab.classList.remove("active");
      inputDiv.classList.remove("active");

      descriptionTab.classList.add("active");

      dislayDiv.classList.add("active");

      inputs = false;
      previews = true;
    }

    const text = document.getElementById('body').value;
    const html = converter.makeHtml(text);

    target.innerHTML = html;
  });
}

// Single comment menu
const singleCommentMenus = document.querySelectorAll('.single .comment > .dropdown');
const singleCommentArrows = document.querySelectorAll('.single > .comments > .comment > .arrow');

singleCommentArrows.forEach((arrow, index) => {
  let menuShow = false;
  arrow.addEventListener('click', () => {
    if (menuShow == false) {
      singleCommentMenus[index].style.transform = 'translateY(0%)';
      arrow.style.transform = 'rotateX(180deg)';
      menuShow = true;
    } else {
      singleCommentMenus[index].style.transform = 'translateY(-100%)';
      arrow.style.transform = 'rotateX(0deg)';
      menuShow = false;
    }
  })
});

// Live preview image in profile
const avatarInput = document.querySelector('.avatar-input');
const avatarPreview = document.querySelector('.user-avatar > img')

if (avatarInput) {
  avatarInput.addEventListener('change', () => {
    const file = avatarInput.files[0];
    if (file) {
      avatarPreview.src = URL.createObjectURL(file);
    }
  })
}

// Submit textarea on enter, new line on shift+enter
if (document.querySelector('textarea')) {
  document.querySelector('textarea').addEventListener("keypress", e => {
    if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault();

        e.currentTarget.closest("form").submit();
    }
  });
}

// Show and hide markdown help on click of help button
const formattingButton = document.querySelector('.formatting-btn');
const formattingHelp = document.querySelector('.formatting-help');
const formattingClose = document.querySelector('.formatting-help > span');

let showFormattingHelp = false;

if (formattingButton) {
  formattingButton.addEventListener('click', () => {
    if (showFormattingHelp) {
      formattingHelp.style.opacity = '0';
      formattingHelp.style.visibility = 'hidden';
      showFormattingHelp = false;
    } else {
      formattingHelp.style.opacity = '1';
      formattingHelp.style.visibility = 'visible';
      showFormattingHelp = true;
    }
  })

  formattingClose.addEventListener('click', () => {
    formattingHelp.style.opacity = '0';
    formattingHelp.style.visibility = 'hidden';
    showFormattingHelp = false;
  })
}

// User role dropdown
const roleArr = document.querySelector('.role-arrow');
const roleDropdown = document.querySelector('.change-role');
const roleList = document.querySelector('.change-role > ul');

let showRoles = false;

if (roleDropdown) {
  roleArr.addEventListener('click', () => {
    if (showRoles) {
      roleDropdown.style.transform = 'scaleY(0%)';
      roleArr.style.transform = 'rotateX(0deg)';
      showRoles = false;
    } else {
      roleDropdown.style.transform = 'scaleY(100%)';
      roleArr.style.transform = 'rotateX(180deg)';
      showRoles = true;
    }
  })

  formattingClose.addEventListener('click', () => {
    formattingHelp.style.opacity = '0';
    formattingHelp.style.visibility = 'hidden';
    showRoles = false;
  })
}