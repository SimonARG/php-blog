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

  window.addEventListener('click', (event) => {
    if (menuShow && !indexMenus[index].contains(event.target) && event.target !== arrow) {
      indexMenus[index].style.transform = 'scaley(0%)';
      arrow.style.transform = 'rotate(0deg)';
      menuShow = false;
    }
  });
});

// Mobile sidebar and header
const navMenu = document.querySelector('.nav-menu');
const menuBtn = document.querySelector('.menu-btn');
const sidebar = document.querySelector('.sidebar.pop')
const sidebarBtn = document.querySelector('.sidebar-btn');
const body = document.querySelector('.body-container');

const wideQuery = window.matchMedia("(min-width: 900px)")

const resetBodyTransform = () => {
  body.style.transform = 'translateX(0%)';
  body.style.bottom = '0';

  setTimeout(() => {
    body.style.transform = '';
    body.style.bottom = '';
  }, 4);
}

document.addEventListener('DOMContentLoaded', () => {
  resetBodyTransform(body);
});

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

    if (!navMenuStatus) {
      resetBodyTransform();
    }
  }
})

const resetBodyOnResize = () => {
  if (wideQuery.matches) { // If media query matches
    resetBodyTransform();
    navMenu.style.transform = 'scaley(100%)';
  } else {
    navMenu.style.transform = 'scaley(0%)';
    navMenuStatus = false;

    sidebar.style.transform = 'scalex(0%)';
    sidebar.style.bottom = 'calc(-4.8rem - 4px)';
    sidebarStatus = false;
  }
}

wideQuery.addEventListener("change", function() {
  resetBodyOnResize();
});

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
const fileInputs = document.querySelectorAll('input[type="file"]');

// Get uploaded file name and write it to the value attribute
const getFileData = (fileField, textField) => {
    const file = fileField.files[0];
    if (file) {
        textField.value = file.name;
    }
}

// Make text input readonly and handle file input changes
const setupFileInput = (fileField, textField) => {
    if (fileField && textField) {
        textField.readOnly = true;

        // Prevent default actions on text input
        ['keydown', 'paste', 'focus', 'mousedown'].forEach(eventType => {
            textField.addEventListener(eventType, (event) => {
                event.preventDefault();
            });
        });

        // Add change event listener to file input
        fileField.addEventListener('change', () => getFileData(fileField, textField));
    }
}

// Setup each file input and its corresponding text field
fileInputs.forEach(fileField => {
    const parent = fileField.parentElement;
    const textField = parent.querySelector(".file-up-field");

    setupFileInput(fileField, textField);
});

// Popup
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
  });
}

// Reports
const reportBtns = document.querySelectorAll('.report-btn');

if (reportBtns) {
  reportBtns.forEach((btn, i) => {
    const reportForm = document.querySelector('.report-' + (i + 1));
    const reportCloser = document.querySelector('.report-' + (i + 1) + ' > span');

    let showReport = false;
    
    btn.addEventListener('click', () => {
      if (showReport == false) {
        reportForm.style.opacity = 1;
        reportForm.style.visibility = 'visible';

        showReport = true;
      } else {
        reportForm.style.opacity = 0;
        setTimeout(() => {
          reportForm.style.visibility = 'hidden';
        }, 200);

        showReport = false;
      }
    })

    reportCloser.addEventListener('click', () => {
      reportForm.style.opacity = 0;
      setTimeout(() => {
        reportForm.style.visibility = 'hidden';
      }, 200);

      showReport = false;
    });

    window.addEventListener('click', (event) => {
      if (showReport && !reportForm.contains(event.target) && event.target !== btn) {
        reportForm.style.opacity = 0;
        setTimeout(() => {
          reportForm.style.visibility = 'hidden';
        }, 200);

        showReport = false;
      }
    });
  });
}

// Popup for new contact mediums, friends and links
const newContactBtn = document.querySelector('.add-contact');
const newContact = document.querySelector('.new-contact');
const closeNewContact = document.querySelector('.new-contact > span');

const newFriendBtn = document.querySelector('.add-friend');
const newFriend = document.querySelector('.new-friend')
const closeNewFriend = document.querySelector('.new-friend > span');

const newLinkBtn = document.querySelector('.add-link');
const newLink = document.querySelector('.new-link');
const closeNewLink = document.querySelector('.new-link > span');

const newPopupForm = (newEl, newBtn, closeBtn) => {
  if (newBtn) {
    let showNewPopup = false;

    newBtn.addEventListener('click', () => {
      if (!showNewPopup) {
        newEl.style.opacity = 1;
        newEl.style.visibility = 'visible';

        showNewPopup = true;
      }
    });

    closeBtn.addEventListener('click', () => {
      newEl.style.opacity = 0;
      setTimeout(() => {
        newEl.style.visibility = 'hidden';
      }, 200); 

      showNewPopup = false;
    });

    window.addEventListener('click', (event) => {
      if (showNewPopup && !newEl.contains(event.target) && event.target !== newBtn) {
        newEl.style.opacity = 0;
        setTimeout(() => {
          newEl.style.visibility = 'hidden';
        }, 200);

        showNewPopup = false;
      }
    });
  }
}

newPopupForm(newContact, newContactBtn, closeNewContact);
newPopupForm(newFriend, newFriendBtn, closeNewFriend);
newPopupForm(newLink, newLinkBtn, closeNewLink);

// Popups for editing contact mediums, friends and links
const editPopupForms = (elements) => {
  elements.forEach(element => {
    const editElementBtn = element.querySelector('.edit');
    const editElement = element.querySelector('.update');
    const closeEditElement = element.querySelector('.close-edit');

    let showEditElement = false;

    if (editElement) {
      editElementBtn.addEventListener('click', () => {
        if (!showEditElement) {
          editElement.style.opacity = 1;
          editElement.style.visibility = 'visible';
    
          showEditElement = true;
        }
      });
  
      closeEditElement.addEventListener('click', () => {
        editElement.style.opacity = 0;
        setTimeout(() => {
          editElement.style.visibility = 'hidden';
        }, 200); 
    
        showEditElement = false;
      });
    }
  
    window.addEventListener('click', (event) => {
      if (showEditElement && !editElement.contains(event.target) && event.target !== editElementBtn) {
        editElement.style.opacity = 0;
        setTimeout(() => {
          editElement.style.visibility = 'hidden';
        }, 200);
  
        showEditElement = false;
      }
    });
  });
}

const contacts = document.querySelectorAll('.contact');
if (contacts) {
  editPopupForms(contacts);
}

const friends = document.querySelectorAll('.friend');
if (friends) {
  editPopupForms(friends);
}

const links = document.querySelectorAll('.link');
if (links) {
  editPopupForms(links);
}

// About edit logic
const about = document.querySelector('.about-container > div');
const aboutEditBtn = document.querySelector('.about-container > div > .edit');
const aboutEditForm = document.querySelector('.about-container > form');
const aboutCancel = document.querySelector('.about-container > form > div > button');

// Resize about textarea
const resizeTextarea = () => {
  const textarea = document.querySelector('.about-container > form > textarea');
  
  // Reset height to auto to get the correct scrollHeight
  textarea.style.height = 'auto';
  
  // Set the height to match the scrollHeight
  textarea.style.height = textarea.scrollHeight + 'px';
}

if (aboutEditBtn) {
  let aboutStatus = true;

  aboutEditBtn.addEventListener('click', () => {
    if (aboutStatus) {
        about.style.opacity = 0;
        setTimeout(() => {
          about.style.visibility = 'hidden';
          about.style.position = 'absolute';
        }, 200);

      setTimeout(() => {
        aboutEditForm.style.position = 'static';
        aboutEditForm.style.opacity = 1;
        aboutEditForm.style.visibility = 'visible';
      }, 200);

      aboutStatus = !aboutStatus;
    } else {
      setTimeout(() => {
        about.style.position = 'static';
        about.style.opacity = 1;
        about.style.visibility = 'visible';
      }, 200);

      aboutEditForm.style.opacity = 0;
      setTimeout(() => {
        aboutEditForm.style.position = 'fixed';
        aboutEditForm.style.visibility = 'hidden';
      }, 200);

      aboutStatus = !aboutStatus;
    }
  });

  aboutCancel.addEventListener('click', (event) => {
    event.preventDefault();

    setTimeout(() => {
      about.style.position = 'static';
      about.style.opacity = 1;
      about.style.visibility = 'visible';
    }, 200);

    aboutEditForm.style.opacity = 0;
    setTimeout(() => {
      aboutEditForm.style.position = 'fixed';
      aboutEditForm.style.visibility = 'hidden';
    }, 200);

    aboutStatus = !aboutStatus;
  })

  window.addEventListener('load', resizeTextarea);
}

// Single report
const reportReview = document.querySelector('.review');
const reportBtn = document.querySelector('.review-report');

if (reportReview) {
  const reportCloser = reportReview.querySelector('span');
  newPopupForm(reportReview, reportBtn, reportCloser);
}