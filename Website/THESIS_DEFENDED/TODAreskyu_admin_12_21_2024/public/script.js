// document.getElementById('menuToggle').addEventListener('click', function () {
//     var navbar = document.getElementById('navbarNav');
//     var container = document.querySelector('.container');

//     navbar.classList.toggle('show');
//     container.classList.toggle('shifted');
// });









// function toggleSubmenu(submenuId) {
//     // Hide all submenus
//     const submenus = document.querySelectorAll('.submenu');
//     submenus.forEach(submenu => {
//         if (submenu.id !== submenuId) {
//             submenu.classList.remove('show');
//         }
//     });

//     // Toggle the clicked submenu
//     document.getElementById(submenuId).classList.toggle('show');
// }

// // Event listeners for Reports
// document.getElementById('arrow-button-1').addEventListener('click', function () {
//     toggleSubmenu('submenu-1');
// });

// document.getElementById('reports-link').addEventListener('click', function (event) {
//     event.preventDefault(); // Prevents the default link behavior
//     toggleSubmenu('submenu-1');
// });

// // Event listeners for Violations
// document.getElementById('arrow-button-2').addEventListener('click', function () {
//     toggleSubmenu('submenu-2');
// });

// document.getElementById('violations-link').addEventListener('click', function (event) {
//     event.preventDefault(); // Prevents the default link behavior
//     toggleSubmenu('submenu-2');
// });

// document.getElementById('arrow-button-3').addEventListener('click', function () {
//     toggleSubmenu('submenu-3')
// });
// document.getElementById('tricycle-link').addEventListener('click', function (event) {
//     event.preventDefault(); // Prevents the default link behavior
//     toggleSubmenu('submenu-3');
// });

// document.getElementById('arrow-button-4').addEventListener('click', function () {
//     toggleSubmenu('submenu-4')
// });
// document.getElementById('commuters-link').addEventListener('click', function (event) {
//     event.preventDefault(); // Prevents the default link behavior
//     toggleSubmenu('submenu-4');
// });

// document.getElementById('arrow-button-5').addEventListener('click', function () {
//     toggleSubmenu('submenu-5')
// });
// document.getElementById('profile-link').addEventListener('click', function (event) {
//     event.preventDefault(); // Prevents the default link behavior
//     toggleSubmenu('submenu-5');
// });






// function toggleSubmenu(submenuId) {
//     // Hide all submenus
//     const submenus = document.querySelectorAll('.submenu');
//     submenus.forEach(submenu => {
//         if (submenu.id !== submenuId) {
//             submenu.classList.remove('show');
//         }
//     });

//     // Toggle the clicked submenu
//     document.getElementById(submenuId).classList.toggle('show');
// }












// document.addEventListener('DOMContentLoaded', function () {
//     // Select the elements
//     const dropdownToggles = document.querySelectorAll('.bi-caret-down-fill');
//     const navLinks = document.querySelectorAll('.nav-link');

//     // Function to handle the active state
//     function setActive(link) {
//         navLinks.forEach(link => link.classList.remove('active'));
//         link.classList.add('active');
//     }

//     // Function to toggle submenu visibility
//     function toggleSubmenu(submenu) {
//         submenu.classList.toggle('d-none');
//     }

//     // Add click event listeners to all navigation links
//     navLinks.forEach(link => link.addEventListener('click', function () {
//         setActive(this);
//     }));

//     // Add click event listener to each dropdown toggle
//     dropdownToggles.forEach(toggle => {
//         toggle.addEventListener('click', function () {
//             // Find the associated submenu
//             const submenuId = `#submenu-${this.id.split('-')[2]}`;
//             const submenu = document.querySelector(submenuId);

//             // Toggle the submenu and set the active state
//             if (submenu) {
//                 toggleSubmenu(submenu);
//             }

//             // Optionally, set active state to the closest link
//             const closestLink = this.closest('.nav-item').querySelector('.nav-link');
//             if (closestLink) {
//                 setActive(closestLink);
//             }
//         });
//     });
// });

// //for tooltips


document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
});




