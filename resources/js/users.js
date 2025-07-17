$(function () {
    let table = $("#users-table").DataTable({
        ajax: "/users",
        processing: true,
        serverSide: true,
        columns: [
            { data: "name" },
            { data: "email" },
            { data: "is_admin" },
            {
                data: "actions",
                orderable: false,
                searchable: false,
            },
        ],
    });

    let modal = $("#userModal");
    let form = $("#user-form");
    let saveBtn = modal.find('button[type="submit"]');

    $("#btn-add").on("click", function () {
        $("#user-id").val("");
        form.trigger("reset");
        $("#userModalLabel").text("Nuevo Usuario");
        modal.modal("show");
    });

    $("#users-table").on("click", ".btn-edit", function () {
        let id = $(this).data("id");
        $.get(`/users/${id}`, function (data) {
            $("#user-id").val(data.id);
            $("#name").val(data.name);
            $("#email").val(data.email);
            $("#password").val("");
            $("#is_admin").prop(
                "checked",
                data.is_admin === 1 || data.is_admin === true
            );
            $("#userModalLabel").text("Editar Usuario");
            modal.modal("show");
        });
    });

    form.on("submit", function (e) {
        e.preventDefault();
        saveBtn.prop("disabled", true);

        let id = $("#user-id").val();
        let isEdit = !!id;
        let url = isEdit ? `/users/${id}` : "/users";
        let method = isEdit ? "PUT" : "POST";

        $.ajax({
            url,
            method: "POST",
            data: {
                _token: $('input[name="_token"]').val(),
                _method: method,
                name: $("#name").val(),
                email: $("#email").val(),
                password: $("#password").val(),
                is_admin: $("#is_admin").is(":checked") ? 1 : 0,
            },
            success: function () {
                modal.modal("hide");
                table.ajax.reload(null, false);
            },
            error: function (xhr) {
                alert("Error: " + xhr.responseText);
            },
            complete: function () {
                saveBtn.prop("disabled", false);
            },
        });
    });

    $("#users-table").on("click", ".btn-delete", function () {
        if (!confirm("¿Eliminar este usuario?")) return;
        let id = $(this).data("id");

        $.ajax({
            url: `/users/${id}`,
            type: "DELETE",
            data: {
                _token: $('input[name="_token"]').val(),
            },
            success: function () {
                table.ajax.reload(null, false);
            },
            error: function () {
                alert("Error al eliminar");
            },
        });
    });
});
