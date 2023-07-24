$(document).ready(function() {    
    var id = $('#id').val() != undefined ? $('#id').val() : null;
    
    if($('form').val() != undefined) {
        $("form").validate({
            rules: {
                name: "required",
                address: "required"
            },
            messages: {
                name: "Nama tidak boleh kosong",
                address: "Alamat tidak boleh kosong",
            },
            onkeyup: function (element) {
                $(element).valid();
            },
            onchange: function (element) {
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
                error.insertAfter(element);
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
        ajax: "/master/warehouse/get-data",
        columns: [
            {data: 'no', name: 'no'},
            {data: 'code', name: 'code'},
            {data: 'name', name: 'name'},
            {data: 'address', name: 'address'},
            {data: 'notes', name: 'notes'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'},
        ],
        columnDefs: [
            { className: "text-center", "targets": [0,1,2,3,4] }
        ],
        fnRowCallback: function( nRow, aData, iDisplayIndex ) {
            $('td', nRow).attr('nowrap','nowrap');
            return nRow;
        }
    });
});

function saveOrUpdate(id = null)
{
    var url = id == null ? "/master/warehouse/store" : " /master/warehouse/"+id;
    var data = $('form').serialize();
    ajaxCall('POST',url,data,'json',function(response) {
        swalSuccess(response.message);
        window.location = "/master/warehouse"
    });
}

function destroy(id) {
    Swal.fire({
        title: 'Apa kamu yakin menghapus gudang ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            var url = "/master/warehouse/"+id;
            ajaxCall('DELETE',url,null,'json',function(response) {
                swalSuccess(response.message);
                window.location = "/master/warehouse"
            });
        }
    })
}