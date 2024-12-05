<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel CRUD</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- Include SweetAlert CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
        <script src="https://cdn.datatables.net/rowreorder/1.5.0/js/dataTables.rowReorder.js"></script>
        <script src="https://cdn.datatables.net/rowreorder/1.5.0/js/rowReorder.bootstrap5.js"></script>
        <link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.bootstrap5.css" rel="stylesheet">
    </head>
    <body class="antialiased">
        <div class="container relative flex items-top justify-center min-h-screen sm:items-center py-4 sm:pt-0">
        <div class="row">
                <div class="col-md-12 text-right">					
                    <button type="button" link="{{ route('addCats') }}" token="{{ csrf_token() }}" class="btn btn-sm btn-primary add_activity" title="Create New">+ Create New</button>
				</div>
                
                <div class="col-md-12 mt-2">
                    <div class="card">                
                        <div class="card-header text-white bg-primary">
                            <i class="fa fa-list"></i>&nbsp; Cats Lists
                        </div>                               
                        <div class="card-body" id="list_input">
							<div class="row">                                
                                <div class="col-md-3 mt-2">
                                    <label class="control-label label-form">Gender</label>
                                    <select id="jenisKelamin" class="form-control select2" style="width:100%" data-placeholder="Please Select" data-allow-clear="true">
                                        <option value=""></option>
                                        <option value="OGJmMjNXZ1RxSEI1aW9XdVpGRDJzQT09">Male</option>
                                        <option value="eHN1M0hKd2hjNHlKczlRSHl0MkxxZz09">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mt-2 align-self-end">
                                    <button type="button" id="btn-filter-status" class="btn btn-primary btn-sm">Apply</button>
                                </div>
                            </div>
							<div class="row">
								<div class="mt-4 table-responsive">
									<table class="table table-bordered table-sm table-striped table_row" id="data-cats" width="100%">
										<thead>
											<tr>
												<th class="all text-center">No.</th>
												<th class="all text-center">Name</th>
												<th class="all text-center">Breed</th>
                                                <th class="all text-center">Gender</th>
											</tr>
										</thead>
										<tbody>

										</tbody>
									</table>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @include('_modal')
        
        <script>
            $("#jenisKelamin").select2({
                placeholder: function() {
                    return $(this).data('placeholder'); // Ambil placeholder dari atribut
                },
                allowClear: true
            });

            $('body').on('click', '.add_activity', function(e)
            {
                var me= $(this),
                    url = me.attr('link'),
                    title = me.attr('title'),
                    token = me.attr('token');
                
                
                $('#content_title').text(title);
                $('#content_save').text(me.hasClass('edit') ? 'Update' : 'Submit');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: { '_token' :  token },
                    // dataType: 'html',
                    success: function(response)
                    {
                        $('#content_body').html(response);
                    },
                    beforeSend: function(msg)
                    {
                        $('#content_body').html("<img src='images/spinner-mini.gif' /> Please wait");
                    },
                    error: function (xhr, error, thrown)
                    {           
                        location.href = "/";
                    }
                });

                var myModal = new bootstrap.Modal(document.getElementById('modal_add'), {
                    // backdrop: 'static',
                    keyboard: false
                });
                myModal.show();

            });

            $(function() {
                $('#modal_add').on('hidden.bs.modal', function (e) {
                    $('#content_body').html('');
                });

                $('#modal').on('hidden.bs.modal', function (e) {
                    $('#modal-body').html('');
                });

            });

        </script>
        <script type="text/javascript">
            $(function() {
                

                var csrf_token = '{{ csrf_token() }}';
                var cats = $('#data-cats').DataTable({
                    
                    processing: true,
                    serverSide: true,
                    ajax: 
                    {
                        url: '{{ route('getCats') }}',
                        data: function (d) {
                            d.gender = $('#jenisKelamin').val();
                            d._token = csrf_token;
                        },
                        method: 'post',
                        error: function (xhr, error, thrown) {
                            
                        }		
                    },
                    columns: [
                        {   data: "id",
                            render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            } 
                        },
                        {   data: 'name', name: 'name',"orderable":false },
                        {   data: 'breed', name: 'breed',"orderable":false },
                        {   data: 'gender', name: 'gender',"orderable":false },
                    ],
                    columnDefs: [{ 
                            "targets": [ 0, 1 ],
                            "orderable": false, 
                        },
                    ],
                            
                    order: [],
                    
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    language: {
                        "search" : "Search : ",
                        "searchPlaceholder" : "Type to search"
                    }
                    
                });
                    
                $('#data-cats tbody').on('click', 'tr', function () {
                    var data = cats.row( this ).data();
                    viewCats(data.id);
                });
                
                $('#btn-filter-status').click(function(){
                    $('#data-cats').DataTable().ajax.reload();
                });
                
            });

            function viewCats(idCat)
            {
                var csrf_token = '{{ csrf_token() }}';
                $.ajax({
                    url:"{{ route('viewCats') }}",
                    method : 'post',
                    dataType : 'json',
                    data: {
                        'idCat' : idCat,
                        '_token' : csrf_token            
                    },
                    success: function(msg){
                        $('#content_body').html(msg.body);
                        $('#content_title').text(msg.title);
                    },
                    beforeSend: function(msg)
                    {
                        $('#content_body').html("<img src='{{ asset('images/spinner-mini.gif') }}'/> Please wait");
                    },
                    error: function (xhr, error, thrown)
                    {
                        
                    }
                });

                var myModal = new bootstrap.Modal(document.getElementById('modal_add'), {
                    // backdrop: 'static',
                    keyboard: false
                });
                myModal.show();
                
            }
        </script>
    </body>
</html>
