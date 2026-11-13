// Función para esperar a que el DOM se cargue completamente antes de ejecutar el script
document.addEventListener("DOMContentLoaded", function () {
    var enrollButtons = document.querySelectorAll('.course-button');

    // Se añade un evento de clic a cada botón de matriculación
    enrollButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            var courseId = event.target.getAttribute('data-course-id');
            var userId = event.target.getAttribute('data-user-id');

            if (event.target.textContent === "Ver curso") {
                window.location.href = "course.php?id=" + courseId;
            } else {
                enrollCourse(courseId, userId);
            }
        });
    });
});

// Función para matricular en un curso mediante una solicitud AJAX
function enrollCourse(courseId, userId) {
    $.ajax({
        url: '../../controllers/course_requests.php',
        type: 'POST',
        data: {
            course_id: courseId,
            user_id: userId
        },
        success: function (response) {
            response = response.trim();

            var modalTitle = "";
            var modalBody = "";

            // Se manejan las respuestas según el estado de la inscripción
            switch (response) {
                case 'success':
                    modalTitle = "Matrícula enviada";
                    modalBody = "Se ha enviado tu solicitud al administrador del curso.";
                    break;

                case 'already_requested':
                    modalTitle = "Solicitud duplicada";
                    modalBody = "Ya has solicitado este curso anteriormente.";
                    break;

                default:
                    return;
            }

            // Se muestra una modal con el resultado de la inscripción
            document.getElementById("enrollmentModalTitle").textContent = modalTitle;
            document.getElementById("enrollmentModalBody").textContent = modalBody;
            $('#enrollmentModal').modal('show');
        },
       // Se manejan los errores en la solicitud AJAX
        error: function () {
            document.getElementById("enrollmentModalTitle").textContent = 'Error';
            document.getElementById("enrollmentModalBody").textContent = 'Hubo un error al procesar tu solicitud. Por favor, inténtalo de nuevo.';
            $('#enrollmentModal').modal('show');
        }
    });
}
