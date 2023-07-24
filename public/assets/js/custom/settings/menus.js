$(document).ready(function () {
    var original_url = $('#original_url').val() != undefined ? $('#original_url').val() : null;
    $('.is_parent').on('change',function(){
        if($('#menu').is(':checked')){
            $('#sub_menu').removeAttr('checked');
            this.setAttribute('checked',"")
            $(".has-parent-menu").prop("hidden", true);
            $("select[name='parent_menu_id']").prop("disabled", true);
        }
        else{
            $('#menu').removeAttr('checked');
            this.setAttribute('checked',"")
            $(".has-parent-menu").prop("hidden", false);
            $("select[name='parent_menu_id']").prop("disabled", false);
            $('#parent_menu_id').valid();
        };
    });

    $.validator.addMethod(
        "checkExistRole",
        function (value, element) {
            var tmp = true;
            var arrUserLevel = $("select[name='role_id[]']")
                .map(function () {
                    if (this.value != "") {
                        return this.value;
                    }
                }).get();
            if (arrUserLevel.length !== new Set(arrUserLevel).size) {
                tmp = false
            }
            return tmp;
        },
        "Peran sudah digunakan."
    );

    var id = $('#id').val() != undefined ? $('#id').val() : null;

    if ($('form').val() != undefined) {
        $("form").validate({
            rules: {
                parent_menu_id: "required",
                title: "required",
                position: "required",
                url: {
                    required: true,
                    remote: {
                        url: "/settings/menus/check-exist-url",
                        type: "post",
                        async: true,
                        data: {
                            parameter: function () {
                                return $(
                                    'form :input[name="url"]'
                                ).val();
                            },
                            original_url: original_url,
                        },
                    }
                },
                'role_id[]': {
                    required: true,
                    checkExistRole: true
                }
            },
            messages: {
                parent_menu_id: "Parent menu tidak boleh kosong.",
                title: "Judul tidak boleh kosong.",
                position: "Posisi Menu tidak boleh kosong.",
                url: {
                    required: "Url tidak boleh kosong.",
                    remote: "Url sudah digunakan."
                },
                'role_id[]': {
                    required: "Peran tidak boleh kosong."
                }
            },
            onkeyup: function (element, event) {
                $(element).valid();
            },
            highlight: function (element) {
                $(element).closest(".form-control").addClass("is-invalid");
            },
            unhighlight: function (element) {
                $(element).closest(".form-control").removeClass("is-invalid");
            },
            errorElement: "div",
            errorClass: "invalid-feedback",
            errorPlacement: function (error, element) {
                if (
                    element.hasClass("select2-custom") &&
                    element.next(".select2-container").length
                ) {
                    error.insertAfter(element.next(".select2-container"));
                } else if (element.parent(".input-group").length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function (form) {
                saveOrUpdate(id);
            },
            invalidHandler: function (form, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {
                    validator.errorList[0].element.focus();
                }
            },
        });

        $('form').valid();
    }

    $('.datatable').dataTable({
        processing: true,
        serverSide: true,
        language : { processing : 'Loading' },
        ajax: "/settings/menus/get-data",
        columns: [
            {data: 'no', name: 'no'},
            {data: 'title', name: 'title'},
            {data: 'url', name: 'url'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'},
        ]
    });
});

function saveOrUpdate(id = null) {
    var url = id == null ? "/settings/menus/store" : " /settings/menus/" + id;
    var data = $('form').serialize();
    ajaxCall('POST',url,data,'json',function(response) {
        swalSuccess(response.message);
        window.location = "/settings/menus"
    })
}

function listRoles(row) {
    var url = "/settings/menus/list-roles";
    ajaxFetchCall(url,'json', function() {},function(response) {
        var html = "<option value=''>Pilih Role</option>";
        $.each(response, function (key, value) {
            html += "<option value='" + value.id + "'>";
            html += value.name;
            html += "</option>";
        });
        $("#role_id_" + row).html(html);
    }, function(jqXHR,textStatus,errorThrown) {
        $("#row_" + row).remove();
    })
}

function isCheckedAccess(obj) {
    if ($("#" + obj.id).is(":checked")) {
        $("#" + $(obj).data("initial")).val("1");
    } else {
        $("#" + $(obj).data("initial")).val("0");
    }
}

function addRow() {
    var counter = $("#counter_row").val();
    var new_counter = parseInt(counter) + 1;

    var html = "<tr id='row_" + new_counter + "' class='text-center'>";
    html += "<td>";
    html += "<input type='hidden' name='row_id[]' id='row_id_" + new_counter + "' value='" + new_counter + "'>"
    html +=
        "<select class='form-control select2-custom' id='role_id_" +
        new_counter +
        "' name='role_id[]'>";
    html += "</select>";
    html +=
        "<div class='invalid-feedback' id='role_id_" +
        new_counter +
        "_error_message'></div>";
    html += "</td>";
    html += "<td class='form-group'>";
    html +=
        "<input class='form-check-input' type='checkbox' data-initial='view_access_" +
        new_counter +
        "' id='view_access_checked_" +
        new_counter +
        "' onclick='isCheckedAccess(this);'>";
    html +=
        "<input class='form-check-input' type='hidden' name='view_access[]' id='view_access_" +
        new_counter +
        "' name='view_access[]' value='0'>";
    html += "</td>";
    html += "<td class='form-group'>";
    html +=
        "<input class='form-check-input' type='checkbox' data-initial='add_access_" +
        new_counter +
        "' id='add_access_checked_" +
        new_counter +
        "' onclick='isCheckedAccess(this);'>";
    html +=
        "<input class='form-check-input' type='hidden' name='add_access[]' id='add_access_" +
        new_counter +
        "' name='add_access[]' value='0'>";
    html += "<td class='form-group'>";
    html +=
        "<input class='form-check-input' type='checkbox' data-initial='edit_access_" +
        new_counter +
        "' id='edit_access_checked_" +
        new_counter +
        "' onclick='isCheckedAccess(this);'>";
    html +=
        "<input class='form-check-input' type='hidden' name='edit_access[]' id='edit_access_" +
        new_counter +
        "' name='edit_access[]' value='0'>";
    html += "</td>";
    html += "<td class='form-group'>";
    html +=
        "<input class='form-check-input' type='checkbox' data-initial='delete_access_" +
        new_counter +
        "' id='delete_access_checked_" +
        new_counter +
        "' onclick='isCheckedAccess(this);'>";
    html +=
        "<input class='form-check-input' type='hidden' name='delete_access[]' id='delete_access_" +
        new_counter +
        "' name='delete_access[]' value='0'>";
    html += "</td>";
    html += "<td>";
    html +=
        "<button class='btn btn-sm btn-danger' onclick=deleteRow('" +
        new_counter +
        "') id='delete_row_" +
        new_counter +
        "'>";
    html += "Hapus";
    html += "</button>";
    html += "</td>";
    html += "</tr>";

    $("#tbl_users_access tbody").append(html);

    listRoles(new_counter);
    select2Custom(".select2-custom");

    $("[name^=role_id]").valid();
    $("select").on("change", function (e) {
        $("#" + this.id).valid();
    });
    $("#counter_row").val(new_counter);
}

function deleteRow(row) {
    $("#row_" + row).remove();
}

function destroy(id) {
    Swal.fire({
        title: 'Apa kamu yakin menghapus menu ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            var url = "/settings/menus/" + id;
            ajaxCall('DELETE',url,null,'json',function(response) {
                swalSuccess(response.message);
                window.location = "/settings/menus"
            });
        }
    })
}
