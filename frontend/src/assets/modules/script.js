export function delegationClick() {
  document.addEventListener('click', documentActions)
  function documentActions(e) {
    const targetElement = e.target;

    //?Открывание и закрывание бургера (обязательно в app.js импортируем функцию closeMenu(), которая находиться здесь ниже)
    if (targetElement.closest('.menu__icon')) {
      const menuBody = document.querySelector('.menu__body');
      menuBody.classList.toggle('open');
      targetElement.closest('.menu__icon').classList.toggle('active');
      if (menuBody.classList.contains('open'))
        document.body.classList.add('lock');
      else
        document.body.classList.remove('lock');

      if (menuBody.classList.contains('open')) {
        window.addEventListener('resize', closeMenu);
      } else {
        window.removeEventListener('resize', closeMenu);
      }
    }
    if (targetElement.closest('.menu__body a')) {
      const activeMenu = targetElement.closest('.menu__body.open');
      if (activeMenu) {
        activeMenu.classList.remove('open');
        const iconMenu = document.querySelector('.menu__icon');
        if (iconMenu) {
          iconMenu.classList.remove('active');
        }
        document.body.classList.remove('lock');
      }
    }

    //?Плавный скрол между якорями (работает вообще на все ссылки)
    if (targetElement.closest('a')) {
      const link = targetElement.closest('a');
      const href = link.getAttribute('href').replace('/', '');

      // Проверяем, является ли href корректным селектором
      const isValidSelector = (str) => {
        try {
          document.querySelector(str);
          return true;
        } catch (e) {
          return false;
        }
      };

      if (isValidSelector(href)) {
        const goToBlock = document.querySelector(href);

        if (goToBlock) {
          let goToBlockValue = goToBlock.getBoundingClientRect().top + scrollY;
          const header = document.querySelector('.header');
          if (header) {
            goToBlockValue -= header.offsetHeight;
          }
          window.scrollTo({
            top: goToBlockValue,
            behavior: "smooth"
          });
          e.preventDefault();
        }
      }
    }

    //?Открывание модального окна (по классу open)
    //атрибуты: data-button-for-open-custom-popup="popup" - кнопка открывания; data-custom-popup="popup" - попап; data-close-for-custom-popup - кнопка закрывания (обязательно внутри попапа);  data-custom-popup-content - контентная оболочка (внутри попапа внутри body попапа).
    if (targetElement.closest("[data-close-for-custom-popup]")) {
      const popup = targetElement.closest("[data-custom-popup]");
      if (popup) {
        popup.classList.remove('open');
        //Устранение дергания при убирании скрола
        document.body.classList.remove("lock");
        document.body.style.paddingRight = 0;
        const header = document.querySelector("header");
        if (header) {
          header.style.paddingRight = 0;
        }

        //убираем поле с текстом услуги
        const inputServiceText = popup.querySelector('#serviceText');
        inputServiceText.value = '';
        inputServiceText.closest('div').classList.add('form__item_none');

        //убираем поле с ценой для торга
        const inputBargainingText = popup.querySelector('#bargainingText');
        inputBargainingText.value = '';
        inputBargainingText.closest('div').classList.add('form__item_none');

        //id услуги убираем в input
        popup.querySelector('#serviceId').value = null
        //id объекта убираем в input
        popup.querySelector('#propertyId').value = null
      }
    }
    if (targetElement.closest("[data-button-for-open-custom-popup]")) {
      const popupName = targetElement.closest("[data-button-for-open-custom-popup]").dataset.buttonForOpenCustomPopup;
      const popup = document.querySelector(`[data-custom-popup="${popupName}"]`);
      if (popup) {
        popup.classList.add('open');
        //Устранение дергания при убирании скрола
        const lockPaddingValue = window.innerWidth - document.body.offsetWidth + 'px';
        document.body.style.paddingRight = lockPaddingValue;
        document.body.classList.add("lock");
        const header = document.querySelector("header");
        if (header) {
          header.style.paddingRight = lockPaddingValue;
        }

        //дополнительно для добавления поля услуги
        const dataServicePopup = targetElement.closest("[data-service-popup]");
        if (dataServicePopup) {
          const textService = dataServicePopup.dataset.servicePopup;

          const inputServiceText = popup.querySelector('#serviceText');
          inputServiceText.value = textService;
          inputServiceText.closest('div').classList.remove('form__item_none');

          //id услуги добавляем в input скрытый в попапе
          const idService = dataServicePopup.dataset.serviceIdPopup;
          if (idService)
            popup.querySelector('#serviceId').value = idService

          //либо если у нас объект, добавляем его id
          const idProperty = dataServicePopup.dataset.propertyIdPopup;
          if (idProperty)
            popup.querySelector('#propertyId').value = idProperty
            const isBargaining = dataServicePopup.dataset.isBargainingPopup;
            if (isBargaining) {
              popup.querySelector('#bargaining').classList.remove('form__item_none')
            }
        }

        e.preventDefault();
      }
    } else if (!targetElement.closest("[data-custom-popup].open [data-custom-popup-content]")) {
      const popup = document.querySelector("[data-custom-popup].open");
      if (popup) {
        popup.classList.remove('open');

        //Устранение дергания при убирании скрола
        document.body.classList.remove("lock");
        document.body.style.paddingRight = 0;
        const header = document.querySelector("header");
        if (header) {
          header.style.paddingRight = 0;
        }

      //убираем поле с текстом услуги
        const inputServiceText = popup.querySelector('#serviceText');
        inputServiceText.value = '';
        inputServiceText.closest('div').classList.add('form__item_none');

        //убираем поле с ценой для торга
        const inputBargainingText = popup.querySelector('#bargainingText');
        inputBargainingText.value = '';
        inputBargainingText.closest('div').classList.add('form__item_none');

        //id услуги убираем в input
        popup.querySelector('#serviceId').value = null
        //id объекта убираем в input
        popup.querySelector('#propertyId').value = null
      }
    }

    //?Табы
    if (targetElement.closest('[data-filter]')) {
      const itemFilter = targetElement.closest('[data-filter]');
      const filterValue = itemFilter.dataset.filter;
      const tabs = itemFilter.closest('[data-tabs]');
      tabs.querySelectorAll('[data-filter]').forEach(item => { item.classList.remove('active') });
      itemFilter.classList.add('active');
      const tabsItems = tabs.querySelectorAll('[data-filter-item]');
      const durationAnimation = 300;
      if (filterValue === "*") {
        tabsItems.forEach(item => {
          if (item.style.position !== 'absolute') {
            item.style.cssText = `opacity: 0;`;
            setTimeout(() => {
              item.style.cssText = `position: absolute;opacity: 0;top: 0;`;
            }, durationAnimation);
          }
        });

        setTimeout(() => {
          tabsItems.forEach(item => {
            item.style.cssText = ``;
            setTimeout(() => { item.style.cssText = `opacity: 1;`; }, 100);
          });
        }, durationAnimation);
      } else {
        tabsItems.forEach(item => {
          if (item.style.position !== 'absolute') {
            item.style.cssText = `opacity: 0;`;
            setTimeout(() => {
              item.style.cssText = `position: absolute;opacity: 0;top: 0;`;
            }, durationAnimation);
          }
        });
        setTimeout(() => {
          tabsItems.forEach(item => {
            if (item.dataset.filterItem === filterValue) {
              item.style.cssText = ``;
              setTimeout(() => { item.style.cssText = `opacity: 1;`; }, 100);
            }
          });
        }, durationAnimation);
      }
      e.preventDefault();
    }

    //?Пульсирующий эффект (Ripple Effect)
    //Надо для работы - атрубыт data-ripple со значение once, если надо только максимум один круг выводить
    //Обязательно в стилях стилизируем и пишем анимацию для span внутри кнопки или ссылки, для которой обязательно указываем pos rel и желательно ov hid
    /*
    a {
        overflow: hidden;
        position: relative;
    }
    span{
        position: absolute;
        border-radius: 50%;
        background-color: #ffffff8c;
        border: 1px solid #ffffff5e;
        animation: pirple-effect 1s ease forwards;
    }
    @keyframes pirple-effect {
        0%{
            transform: scale(0);
        }
        100%{
            transform: scale(1);
            opacity: 0;
        }
    }
    */
    //Основной код
    /* if (targetEelment.closest('[data-ripple]')) {
        const button = targetEelment.closest('[data-ripple]');
        const ripple = document.createElement('span');
        const diameter = Math.max(button.clientWidth, button.clientHeight);
        const radius = diameter / 2;

        ripple.style.width = ripple.style.height = `${diameter}px`;
        ripple.style.left = `${e.pageX - (button.getBoundingClientRect().left + scrollX) - radius}px`;
        ripple.style.top = `${e.pageY - (button.getBoundingClientRect().top + scrollY) - radius}px`;

        button.dataset.ripple === 'once' && button.querySelector('span') ? button.querySelector('span').remove() : null;

        button.appendChild(ripple);

        const timeOut = getAnimationDuration(ripple);

        setTimeout(() => {
            ripple ? ripple.remove() : null;
        }, timeOut);

        function getAnimationDuration() {
            const aDuration = window.getComputedStyle(ripple).animationDuration;
            return aDuration.includes('ms') ? aDuration.replace('ms', '') : aDuration.replace('s', '') * 1000;
        }
    } */
  }
}
//фу-ия для закрывания меню в случае,если ширина экрана стала больше, чем ширина, при которой отображается меню, иначе у нас будет заблокан скролл
//!поменять 767.98 на другое значение, если нужно (992.98)
export function closeMenu() {
  if (window.innerWidth > 767.98) {
    const activeIcon = document.querySelector('.menu__icon.active');
    if (activeIcon) {
      activeIcon.classList.remove('active');
      document.querySelector(".menu__body.open").classList.remove("open");
      document.body.classList.remove('lock');
    }
  }
}
//Анимация шакпи при скролле
export function headerScroll() {
  const header = document.querySelector('.header');
  if (header) {
    window.scrollY > 0 ? header.classList.add('scroll') : null;

    window.addEventListener('scroll', () => {
      window.scrollY > 0 ? header.classList.add('scroll') : header.classList.remove('scroll');
    });
  }
}
//Если страница проскролена на более 100 пкс, то шапка исчезает, если скролиться вверх, то появляется
export function ScrollHeader() {
  const header = document.querySelector('.header');
  let scrollPrev = 0;
  if (header) {
    window.addEventListener('scroll', () => {
      window.scrollY > 100 && scrollPrev < window.scrollY ? header.classList.add('out') : header.classList.remove('out');
      scrollPrev = window.scrollY;
    });
  }
}
