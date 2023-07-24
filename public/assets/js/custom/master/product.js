$(document).ready(function() {    
    var id = $('#id').val() != undefined ? $('#id').val() : null;
    
    if($('form').val() != undefined) {
        $("form").validate({
            rules: {
                name: "required",
                code: "required",
                
                product_type_id: "required",
                product_brand_id: "required",
                product_divisi_id: "required",
                product_category_id: "required",
                product_subcategory_id: "required",
                stock_unit_name: "required",
                small_unit: "required",
            },
            messages: {
                code: "Kode tidak boleh kosong",
                name: "Nama tidak boleh kosong",
                product_type_id: "Jenis tidak boleh kosong",
                product_brand_id: "Brand tidak boleh kosong",
                product_divisi_id: "divisi tidak boleh kosong",
                product_category_id: "Kategori tidak boleh kosong",
                product_subcategory_id: "Sub Kategori tidak boleh kosong",
                stock_unit_name: "Satuan Stock tidak boleh kosong",
                small_unit: "Satuan Kecil tidak boleh kosong",
            },
            onkeyup: function (element) {
                $(element).valid();
            },
            onblur: false,
            onfocusout: false,
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
        ajax: "/master/products/get-data",
        columns: [
            {data: 'no', name: 'no'},
            {data: 'divisi', name: 'divisi'},
            {data: 'category', name: 'category'},
            {data: 'subcategory', name: 'subcategory'},
            {data: 'brand', name: 'brand'},
            {data: 'code', name: 'code'},
            {data: 'name', name: 'name'},
            {data: 'unit', name: 'unit'},
            {data: 'price', name: 'price'},
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

function getSubCategoryByCategory(obj)
{
    var category_id = obj.value == '' ? null : obj.value;
    var url = "/master/products/list-subcategory/"+category_id;
    ajaxFetchCall(url,'json', function() {
        swalLoading();
    },function(response) {
        var html = "<option value=''>Pilih Sub Kategori</option>";
        $.each(response.data, function (key, value) {
            html += "<option value='" + value.id + "'>";
            html += value.name;
            html += "</option>";
        });
        $("#product_subcategory_id").html(html);
    }, function(jqXHR,textStatus,errorThrown) {
        swalToastError(jqXHR.status + ' : ' + errorThrown);
    })
}

function saveOrUpdate(id = null)
{
    var url = id == null ? "/master/products/store" : " /master/products/"+id;
    var data = new FormData($('form')[0]);
    ajaxCall('POST',url,data,'json',function(response) {
        swalSuccess(response.message);
        console.log(response)
        window.location = "/master/products"
    }, true);
}

function destroy(id) {
    Swal.fire({
        title: 'Apa kamu yakin menghapus produk ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            var url = "/master/products/"+id;
            ajaxCall('DELETE',url,null,'json',function(response) {
                swalSuccess(response.message);
                window.location = "/master/products"
            });
        }
    })
}