//Toggle sidebar
function toggleSidebar() {
   document.querySelector('body').classList.toggle('sidebar-mini');
}

//Togle Navbar
function toggleNavbar() {
   document.querySelector('.navbar-nav').classList.toggle('show');
}

//Toggle menu
document.querySelector('.dropdown-toggle').addEventListener('click', function () {
   document.querySelector('.dropdown-menu').classList.toggle('show');
});


(function () { //dropdown
   'use strict'

   var dropdowns = document.querySelectorAll('.dropdown-toggle')

   // Itera sobre cada elemento de dropdown y agrega un listener al evento 'click'
   Array.prototype.forEach.call(dropdowns, function (dropdown) {
      dropdown.addEventListener('click', function (event) {
         // Evita que el navegador siga el enlace de la opción del menú
         event.preventDefault()

         // Abre o cierra el menú dependiendo de su estado actual
         var isOpen = this.parentElement.classList.contains('show')
         if (isOpen) {
            this.parentElement.classList.remove('show')
            this.setAttribute('aria-expanded', 'false')
            this.nextElementSibling.classList.remove('show')
         } else {
            this.parentElement.classList.add('show')
            this.setAttribute('aria-expanded', 'true')
            this.nextElementSibling.classList.add('show')
         }
      })

   })

   // Cierra el menú si se hace clic en cualquier lugar fuera del menú
   document.addEventListener('click', function (event) {
      var target = event.target
      if (!target.closest('.dropdown')) {
         var dropdowns = document.querySelectorAll('.dropdown')
         Array.prototype.forEach.call(dropdowns, function (dropdown) {
            dropdown.classList.remove('show')
            dropdown.querySelector('.dropdown-toggle').setAttribute('aria-expanded', 'false')
            dropdown.querySelector('.dropdown-menu').classList.remove('show')
         })
      }
   })

   if (window.innerWidth < 768) {
      document.querySelector('body').classList.add('sidebar-mini')
   }

   // Cierra el menú si se presiona 'Escape'
   document.addEventListener('keydown', function (event) {
      var dropdown = event.target.closest('.dropdown')
      if (event.key === 'Escape' && dropdown) {
         dropdown.classList.remove('show')
         dropdown.querySelector('.dropdown-toggle').setAttribute('aria-expanded', 'false')
         dropdown.querySelector('.dropdown-menu').classList.remove('show')
         dropdown.querySelector('.dropdown-toggle').focus()
      }
   })
})()