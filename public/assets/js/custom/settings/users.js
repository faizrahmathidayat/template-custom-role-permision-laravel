$(document).ready(function() {
    var id = $('#id').val() != undefined ? $('#id').val() : null;

    if($('form').val() != undefined) {
        $("form").validate({
            rules: {
                name: "required",
                user: "required",
                pass : {
                    required : true,
                    minlength : 8
                },
                role_id : "required"
            },
            messages: {
                name: "Nama tidak boleh kosong",
                user: "Username tidak boleh kosong",
                pass : {
                    required : "Password tidak boleh kosong",
                    minlength : "minimal password 8 huruf."
                },
                role_id : "Peran tidak boleh kosong"
            },
            onkeyup: function(element) {
                $(element).valid();
            },
            onchange: function(element) {
                $(element).valid();
            },
            highlight: function(element) {
                $(element).closest(".form-control").addClass("is-invalid");
            },
            unhighlight: function(element) {
                $(element).closest(".form-control").removeClass("is-invalid");
            },
            errorElement: "div",
            errorClass: "invalid-feedback",
            errorPlacement: function(error, element) {
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
            submitHandler: function(form) {
                saveOrUpdate(id);
            },
            invalidHandler: function(form, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {
                    validator.errorList[0].element.focus();
                }
            },
        });
        if(id != null) {
            $('#pass').rules("remove", "required");   
        }
        $('form').valid();
    }

    $('.datatable').DataTable({
        search: {
            return: true,
        },
        processing: true,
        serverSide: true,
        ajax: "/settings/users/get-data",
        columns: [
            {data: 'no', name: 'no'},
            {data: 'name', name: 'name'},
            {data: 'username', name: 'username'},
            {data: 'role', name: 'role'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'},
        ],
        columnDefs: [
            { className: "text-center", "targets": [3,4,5] }
        ],
        fnRowCallback: function( nRow, aData, iDisplayIndex ) {
            $('td', nRow).attr('nowrap','nowrap');
            return nRow;
        }
    });
});

function saveOrUpdate(id = null) {
    var url = id == null ? "/settings/users/store" : " /settings/users/"+id;
    var data = $('form').serialize();
    ajaxCall('POST',url,data,'json', function(response) {
        swalSuccess(response.message);
        window.location = "/settings/users"
    })
}

function destroy(id) {
    Swal.fire({
        title: 'Apa kamu yakin menghapus pengguna ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            ajaxCall('DELETE',"/settings/users/"+id,null,'json',function(response) {
                swalSuccess(response.message);
                window.location = "/settings/users"
            })
        }
    })
}