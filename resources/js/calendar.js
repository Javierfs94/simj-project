import $ from "jquery";
import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin, { Draggable } from "@fullcalendar/interaction";
import esLocale from "@fullcalendar/core/locales/es";

let draggableInstance = null;

$(function () {
    // Inicializar calendario
    const calendarEl = document.getElementById("calendar");

    // Cargar usuarios en select antes de iniciar calendario
    $.get("/users/list", function (users) {
        const userSelect = $("#task-user, #user-filter");
        userSelect.empty(); // Limpiar previos
        users.forEach((user) => {
            userSelect.append(
                `<option value="${user.id}">${user.name}</option>`
            );
        });

        const currentUserId = $('meta[name="user-id"]').attr("content");
        $("#task-user").val(currentUserId);
        $("#user-filter").val(currentUserId);

        // 👇 Inicializar el calendario solo después de tener el usuario por defecto
        const calendarEl = document.getElementById("calendar");

        const calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, interactionPlugin],
            initialView: "dayGridMonth",
            locale: esLocale,
            editable: true,
            droppable: true,
            events: function (fetchInfo, successCallback, failureCallback) {
                const userId = $("#user-filter").val();

                $.ajax({
                    url: "/tasks",
                    data: { user_id: userId },
                    success: successCallback,
                    error: failureCallback,
                });
            },
            drop: function (info) {
                const projectId = info.draggedEl.dataset.projectId;
                const dateStr = info.dateStr;

                $("#task-project-id").val(projectId);
                $("#task-start").val(dateStr + "T09:00");
                $("#task-end").val(dateStr + "T17:00");
                $("#task-description").val("");
                $("#taskModal").modal("show");
            },
            eventClick: function (info) {
                const event = info.event;

                $("#detail-project").text(
                    event.extendedProps.project_name || "Sin proyecto"
                );
                $("#detail-user").text(event.extendedProps.user_name || "");
                $("#detail-start").text(event.startStr);
                $("#detail-end").text(event.endStr);
                $("#detail-description").text(
                    event.extendedProps.description || ""
                );

                $("#taskDetailModal").modal("show");
            },
        });

        calendar.render();

        $("#user-filter").on("change", function () {
            calendar.refetchEvents();
        });

        // Guardar referencia global si lo necesitas luego
        window.calendar = calendar;
    });
    loadProjects(); // Cargar proyectos al iniciar

    // Crear tarea
    $("#task-form").on("submit", function (e) {
        e.preventDefault();

        $.post(
            "/tasks",
            {
                _token: document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                description: $("#task-description").val(),
                start: $("#task-start").val(),
                end: $("#task-end").val(),
                project_id: $("#task-project-id").val(),
                user_id: $("#task-user").val() || $("#user-filter").val(),
            },
            function () {
                $("#taskModal").modal("hide");
                calendar.refetchEvents();
            }
        );
    });

    // Cargar usuarios en select
    $.get("/users/list", function (users) {
        const userSelect = $("#task-user, #user-filter");
        userSelect.empty(); // Limpiar previos
        users.forEach((user) => {
            userSelect.append(
                `<option value="${user.id}">${user.name}</option>`
            );
        });

        const currentUserId = $('meta[name="user-id"]').attr("content");
        $("#task-user").val(currentUserId);
        $("#user-filter").val(currentUserId);
    });

    $("#user-filter").on("change", function () {
        calendar.refetchEvents();
    });

    // Modal de proyecto
    const modal = $("#projectModal");
    const form = $("#project-form");

    $("#btn-add-project").on("click", function () {
        form.trigger("reset");
        modal.modal("show");
    });

    form.on("submit", function (e) {
        e.preventDefault();

        $.post(
            "/projects",
            {
                _token: $('input[name="_token"]').val(),
                name: $("#project-name").val(),
            },
            function () {
                modal.modal("hide");
                loadProjects(); // Recargar proyectos en vista
            }
        ).fail(function (xhr) {
            alert("Error: " + xhr.responseText);
        });
    });

    $("#btn-generate-report").on("click", function () {
        $("#reportModal").modal("show");
    });
});

// Función segura para cargar proyectos y evitar duplicaciones
function loadProjects() {
    const container = document.getElementById("external-projects");

    // Limpiar el contenedor y cualquier instancia previa
    container.innerHTML = "";

    $.get("/projects/list", function (projects) {
        projects.forEach((project) => {
            const el = document.createElement("div");
            el.className = "fc-event alert bg-warning alert-primary";
            el.innerHTML = `<strong>${project.name}</strong><br><small>Creado por ${project.creator}</small>`;
            el.dataset.projectId = project.id;
            container.appendChild(el);
        });

        // Crear solo una nueva instancia de Draggable
        draggableInstance = new Draggable(container, {
            itemSelector: ".fc-event",
            eventData: function (el) {
                return {
                    description: el.innerText,
                    project_id: el.dataset.projectId,
                };
            },
        });
    });
}
