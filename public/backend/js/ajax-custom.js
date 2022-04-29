/*Copyright (c) 2022, Mypcot Infotech (https://www.mypcot.com/) */ 
// window.location.reload();
$('#dropdownBasic3').on('click',function(){
    if($('.dropdownBasic3Content').hasClass('show')) {
        $('.dropdownBasic3Content').removeClass('show');
    } else {
        $('.dropdownBasic3Content').addClass('show');
    }
});
$(document).ready(function(){
    $('.select2').select2();
    $('#listing-filter-toggle').on('click',function(){
        $('#listing-filter-data').toggle();
    });
    $('#clear-form-data').on('click',function(){
        $('#listing-filter-data .form-control').val('');
        $('#listing-filter-data .select2').val('').trigger('change');
    });
        
    $(document).on('change', '#approval_status', function(){
        var status = document.getElementById("approval_status").value;
        console.log(status);
        if ( this.value == 'rejected')
        {
          $("#remark").show();
        }
        else
        {
          $("#remark").hide();
        }
    });
    setTimeout(function() {
        $('.successAlert').fadeOut('slow');
    }, 1000); // <-- time in milliseconds

    var items = [];
    var html_table_data = ""; 
    var data_orderable = ""; 
    var data_searchable = "";  
    var bRowStarted = true;
    $('#dataTable thead>tr').each(function () {
        $('th',this).each(function () {
            html_table_data = $(this).attr('id');
            data_orderable = $(this).attr('data-orderable');
            data_searchable = $(this).attr('data-searchable');
            if(html_table_data == 'id')
            {
                items.push({data: 'DT_RowIndex', orderable: false, searchable: false});
            }
            else
            {
                if(data_orderable == 'true') {
                    if(data_searchable == 'true') {
                        items.push({'data':html_table_data, orderable: true, searchable: true});
                    } else {
                        items.push({'data':html_table_data, orderable: true, searchable: false});
                    }
                }
                else {
                    if(data_searchable == 'true') {
                        items.push({'data':html_table_data, orderable: false, searchable: true});
                    } else {
                        items.push({'data':html_table_data, orderable: false, searchable: false});
                    }
                }
            }
            
        });
    }); 
    coldata = JSON.stringify(items);
    $(function() {
        var dataTable = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            autoWidth: true,
            scrollCollapse: true,
            searching: false,
            ajax: {
                url: $('#dataTable').attr('data-url'),
                type: 'POST',
                data: function(d) {
                    d._token = $('meta[name=csrf-token]').attr('content');
                    $("#listing-filter-data .form-control").each(function(){
                        d.search[$(this).attr('id')] = $(this).val();
                    });
                }
            },
            columns: items,
            "drawCallback": function( settings ) {
                $('#dataTable_filter').addClass('pull-right');
                $('#dataTable_filter input').attr('name','search_field');
                var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
                elems.forEach(function(html) {
                    var switchery = new Switchery(html, { color: '#11c15b', jackColor: '#fff', size: 'small', secondaryColor: '#ff5251'});
                });
            },
        });

        $('#listing-filter-data .form-control').keyup(function(){
            dataTable.draw();
        });

        $('#listing-filter-data select').change(function(){
            dataTable.draw();
        });

        $('#clear-form-data').click(function(){
            dataTable.draw();
        });
        
    });
    
    $(document).on('click', '.src_data', function(event){
        event.preventDefault();
        var page = $(this).attr('href');
        loadViewPage(page);
    });

    $(document).on('click', '.modal_src_data', function(event){
        event.preventDefault();
        var page = $(this).attr('href');
        var dataSize = $(this).attr('data-size');
        var dataTitle = $(this).attr('data-title');
        loadViewPageInModal(page,dataSize,dataTitle);
    });
});

function loadViewPageInModal(page_url,dataSize,dataTitle)
{
    $.ajax({
        url: page_url,
        async: true,
        success: function (data) { 
            bootbox.dialog({
                message: data,
                title: dataTitle,
                size: dataSize,
                buttons: false
            });
        }
    });
}

function loadViewPage(page_url)
{
	$.ajax({
        url: page_url,
        datatype : "application/json",
        contentTypech : "application/json",
        async: true,
        success: function (data) { 
            var viewData = data; 
            try {
                if (JSON.parse(data)['success']) {
                    $.activeitNoty({
                        type: 'danger',
                        icon : 'fa fa-minus',
                        message : JSON.parse(data)['message'],
                        container : 'floating',
                        timer : 3000
                    });
                }
            } catch (e) {
                $('.content-wrapper').html(data);
                //to make generic function: future implementation
                if(document.getElementById("approval_status")) {
                    var status = document.getElementById("approval_status").value;
                    ( status == 'rejected') ? $("#remark").show() : $("#remark").hide();
                }
            }
        }
    });
}

function submitForm(form_id,form_method,errorOverlay='')
{
    var form = $('#'+form_id);
    var formdata = false;
    if (window.FormData){
        formdata = new FormData(form[0]);
    }
    $.ajax({
        url: form.attr('action'),
        type: form_method,
        dataType: 'html',
        data: formdata ? formdata : form.serialize(),
        cache       : false,
        contentType : false,
        processData : false,
        success: function(data) {
            var response = JSON.parse(data);
            if(response['success'] == 0)
            {
                if(errorOverlay) {
                    $(form).find('.form-error').html('<span class="text-danger">*'+response['message']+'</span>');
                    setTimeout(function() {
                        $(form).find('.form-error').empty();
                    }, 3000);
                } else {
                    $.activeitNoty({
                        type: 'danger',
                        icon : 'fa fa-minus',
                        message : response['message'],
                        container : 'floating',
                        timer : 3000
                    });
                }
            } else {
                if(errorOverlay) {
                    $(form).find('.form-error').html('<span class="text-success">'+response['message']+'</span>');
                } else {
                    $.activeitNoty({
                        type: 'success',
                        icon : 'fa fa-check',
                        message : response['message'],
                        container : 'floating',
                        timer : 3000
                    });
                }
                setTimeout(function() {
                location.reload();
                }, 2000);
            }
        }
    });
}

//FOR CkEditor data pass to server - added by sagar - START 
function submitEditor(form_id){
    var content = theEditor.getData();
    var form = $('#'+form_id);
    var formdata = false;
    if (window.FormData){
        formdata = new FormData(form[0]);
    }
    $.ajax({
        url: form.attr('action'),
        type: 'post',
        dataType: 'html',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: form.serialize()+'&editiorData='+content,
        success: function(data) {   
            // console.log(data);
            var response = JSON.parse(data);
            if(response['success'] == 0)
            {
                $.activeitNoty({
                    type: 'danger',
                    icon : 'fa fa-minus',
                    message : response['message'],
                    container : 'floating',
                    timer : 3000
                });
            } else {
                $.activeitNoty({
                    type: 'success',
                    icon : 'fa fa-check',
                    message : response['message'],
                    container : 'floating',
                    timer : 3000
                });
              
            }
            setTimeout(function() {
                location.reload();
            }, 2000);
        }
    });
}

//FOR CkEditor data pass to server - added by sagar - END 
$(document).on('click', '#addStock', function(event){
    var trlen = $('#batchTbl tbody tr').length;
    if(trlen == 0)
    {
        var i = trlen;
    }
    else
    {
        var i = parseInt($('#batchTbl tbody tr:last-child').attr('id').substr(10))+1;
    }
    $('#batchTbl').append('<tr id="batchTblTr'+i+'">'+
        '<td><input class="form-control" id="batch_code'+i+'" name="batch_code[]"></td>'+
        '<td><input class="form-control" id="stock'+i+'" name="stock[]"></td>'+
        '<td><button type="button" class="btn btn-danger btn-sm" id="removeStock'+i+'" onclick="remove_batch_tbl_row('+i+')"><i class="fa fa-minus"></i></button></td>'+
    '</tr>');
});
function remove_batch_tbl_row(i)
{
    $('#batchTblTr'+i).remove();
}
$(document).on('click', '.delimg', function(event){
    var ib = $(this).attr('data-id');
    var url = $(this).attr('data-url');
    var main_id = $(this).attr('id');
    bootbox.confirm({
        message: "Are you sure you want to delete this image?",
        buttons: {
            confirm: {
                label: 'Yes, I confirm',
                className: 'btn-primary'
            },
            cancel: {
                label: 'Cancel',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if(result)
            {
                $.ajax({
                    type: 'post',
                    url: url,
                    data: {
                        'ib': ib,
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(data)
                    {
                        var response = JSON.parse(data);
                        if(response['success'] == 0)
                        {
                            $.activeitNoty({
                                type: 'danger',
                                icon : 'fa fa-minus',
                                message : response['message'],
                                container : 'floating',
                                timer : 3000
                            });
                        } else {
                            $.activeitNoty({
                                type: 'success',
                                icon : 'fa fa-check',
                                message : response['message'],
                                container : 'floating',
                                timer : 3000
                            });
                            $('#'+main_id).closest('.main-del-section').remove();
                        }
                    }
                });
            }
        }
    });
});

/**
 * -- CKEditor Textarea box
*/
let theEditor;
function loadCKEditor(id)
{
    $('.ck-editor').remove();
    ClassicEditor.create( document.querySelector( '#'+id ), {
        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
            ]
        }
    } )
    .then(editor => {
        theEditor = editor;
    })
    .catch( error => {
        console.log( error );
    } );
}

function getDataFromTheEditor() {
    return theEditor.getData();
}

 //getProductDetails function with Ajax to get product details drop down of selected product in recommendation engine add|edit
 function getProductDetails(product){
    $.ajax({
        url:"getProductDetailsDropdown",
        type: "POST",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {
            product_id: product
        },
        success:function(result)
        {
            response = JSON.parse(result);
            var category_id = response['data']['category']['id'];
            var category_name = response['data']['category']['category_name'];
            var product_form_id = response['data']['product_form']['id'];
            var product_form_name = response['data']['product_form']['product_form_name'];
            var packaging_treatment_id = response['data']['packaging_treatment']['id'];
            var packaging_treatment_name = response['data']['packaging_treatment']['packaging_treatment_name'];

            $("#product_category").html('<option value="'+category_id+'"">'+category_name+'</option>');
            $("#product_form").html('<option value="'+product_form_id+'"">'+product_form_name+'</option>');
            $("#packaging_treatment").html('<option value="'+packaging_treatment_id+'"">'+packaging_treatment_name+'</option>');
        },
    });  
} 