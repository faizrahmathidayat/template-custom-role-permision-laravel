$(document).ready(function() {    
    var id = $('#id').val() != undefined ? $('#id').val() : null;
    
    if($('form').val() != undefined) {
        $("form").validate({
            rules: {
                qty: {
                    required : true,
                    number : true,
                    min : 1
                },
                total_sdm: {
                    required : true,
                    number : true,
                    min : 1
                },
                subcategory_id: "required",
            },
            messages: {
                subcategory_id: "Sub Kategori tidak boleh kosong",
                qty: {
                    required : "Qty tidak boleh kosong",
                    number : "Qty harus berupa angka",
                    min : "Qty harus lebih besar dari 0"
                },
                total_sdm: {
                    required : "Jumlah SDM tidak boleh kosong",
                    number : "Jumlah SDM harus berupa angka",
                    min : "Jumlah SDM harus lebih besar dari 0"
                },
            },
            onkeyup: function (element) {
                $(element).valid();
            },
            onchange: function (element) {
                $(element).valid();
            },
            onblur: false,
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
        ajax: "/master/production-capacity/get-data",
        columns: [
            {data: 'no', name: 'no'},
            {data: 'subcategory', name: 'subcategory'},
            {data: 'qty', name: 'qty'},
            {data: 'total_sdm', name: 'total_sdm'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'},
        ],
        columnDefs: [
            { className: "text-center", "targets": ['_all'] }
        ],
        fnRowCallback: function( nRow, aData, iDisplayIndex ) {
            $('td', nRow).attr('nowrap','nowrap');
            return nRow;
        }
    });
});

function saveOrUpdate(id = null)
{
    var url = id == null ? "/master/production-capacity/store" : " /master/production-capacity/"+id;
    var data = $('form').serialize();
    ajaxCall('POST',url,data,'json',function(response) {
        swalSuccess(response.message);
        // window.location = "/master/production-capacity"
    });
}

function destroy(id) {
    Swal.fire({
        title: 'Apa kamu yakin menghapus Kapasitas Produksi ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            var url = "/master/production-capacity/"+id;
            ajaxCall('DELETE',url,null,'json',function(response) {
                swalSuccess(response.message);
                window.location = "/master/production-capacity"
            });
        }
    })
}