// Variables globales para controlar las acciones
let actionOnUsers = false;
let actionOnCourses = false;

// Función para cambiar entre pestañas
function openTab(evt, tabName) {
    var i, tabcontent, tabbuttons;
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].classList.remove("active");
    }

    tabbuttons = document.getElementsByClassName("tab-button");
    for (i = 0; i < tabbuttons.length; i++) {
        tabbuttons[i].classList.remove("active");
    }

    document.getElementById(tabName).classList.add("active");
    evt.currentTarget.classList.add("active");

    // Se muestran los botones según la pestaña activa
    if (tabName === "users") {
        document.getElementById('createUserBtn').style.display = 'inline-block';
        document.getElementById('createCourseBtn').style.display = 'none';

    } else if (tabName === "courses") {
        document.getElementById('createUserBtn').style.display = 'none';
        document.getElementById('createCourseBtn').style.display = 'inline-block';
    }
}

// Funciones para registrar acciones de usuarios y cursos
function registerUserAction() {
    actionOnUsers = true;
    const usuarioTab = document.querySelector("button[data-tab='users']");
    usuarioTab.click();
}

function registerCourseAction() {
    actionOnCourses = true;
    const cursoTab = document.querySelector("button[data-tab='courses']");
    cursoTab.click(); 
}

document.addEventListener("DOMContentLoaded", function() {
    // Al cargar la página, se muestra la pestaña de cursos por defecto
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('tab')) {
        const tab = urlParams.get('tab');
        if (tab === 'course') {
            registerCourseAction()
        } else if (tab === "users") {
            registerUserAction()
        }
    } else {
        // Se muestra la pestaña activa por defecto
        const activeTab = document.querySelector(".tab-button.active");
        if (activeTab) {
            activeTab.click();
        }
    }    
});







