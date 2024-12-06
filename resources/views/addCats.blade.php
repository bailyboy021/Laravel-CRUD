<div class="row">
	<div class="col-md-12">	
		{!! Form::model($model,[
			'route' => $model->exists? ['updateCats', $model->id] : 'storeCats',
			'method' => $model->exists? 'PUT' : 'POST',
			'files' => true,
			'id' => 'request_form'
		]) !!}	
			<input type="hidden" id="catId" name="catId" value="{{ $model->exists? $model->id : '0'}}">		
			<div class="form-group row mb-2">
				<div class="col-md-4">
					<label class="control-label">Cats Name</label>
				</div>
				<div class="col-md-8">
					<input type="text" name="name" id="name" class="form-control" placeholder="Cats Name" value="{{ $model->name }}">
				</div>
			</div>
			<div class="form-group row mb-2">
				<div class="col-md-4">
					<label class="control-label">Cats Breed</label>
				</div>
				<div class="col-md-8">
					<input type="text" name="breed" id="breed" class="form-control" placeholder="Cats Breed" value="{{ $model->breed }}">
				</div>
			</div>
			<div class="form-group row mb-2">
				<div class="col-md-4">
					<label class="control-label">Gender</label>
				</div>
				<div class="col-md-8">
					<select class="form-control select2 gender2" name="gender" id="gender" data-placeholder="Please Select" style="width:100%" data-allow-clear="true">
						<option value=""></option>
						<option value="OGJmMjNXZ1RxSEI1aW9XdVpGRDJzQT09" @if($model->gender == 1) selected @endif>Male</option>
						<option value="eHN1M0hKd2hjNHlKczlRSHl0MkxxZz09" @if($model->gender == 2) selected @endif>Female</option>
					</select>
				</div>
			</div>
			
			<div id='loadingmessage' class="col-md-12 mt-2 text-center" style="display: none;">
				<img src="{{ asset('images/spinner-mini.gif') }}"/> Please wait
			</div>
			<div class="text-right d-flex justify-content-end">
				@if($model->exists)
					<button type="submit" class="btn btn-primary btn-sm me-2" id="updated_btn">SAVE</button>
					<button type="button" class="btn btn-sm btn-danger" onclick="btn_delete({{ $model->id }});">DELETE</button>
				@else
					<button type="submit" class="btn btn-primary btn-sm" id="submit_btn">CREATE</button>
				@endif
			</div>
		{!! Form::close() !!}
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $("#gender").select2({
        dropdownParent: $("#modal_add"), // Pastikan ID modal sesuai
        placeholder: function() {
			return $(this).data('placeholder');
		},
        allowClear: true
    });

    $('#request_form').on('submit', function(e) {
        e.preventDefault();

        var form = $('#request_form'),
            url = form.attr('action'),
            modalAdd = bootstrap.Modal.getInstance(document.getElementById('modal_add'));

        form.find('.invalid-feedback').remove();
        form.find('.form-control').removeClass('is-invalid');
        $('#loadingmessage').show();
        $("#submit_btn").hide();

        if ($(".gender2").val() != '') {
            $(".gender2 + span").removeClass("is-invalid");
        }

        $.ajax({
            url: url,
            method: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(returnData) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    // text: 'Data has been successfully submitted!',
                    allowOutsideClick: false
                }).then(function() {
                    modalAdd.hide();
                    $('#data-cats').DataTable().ajax.reload(); // Reload DataTable
                });

                $('#loadingmessage').hide();
                $("#submit_btn").show();
            },
            error: function(xhr) {
                var res = xhr.responseJSON;
                if ($.isEmptyObject(res) == false) {
                    $.each(res.errors, function(key, value) {
                        $('#' + key).closest('.form-control').addClass('is-invalid');
                    });
                }

                $('#loadingmessage').hide();
                $("#submit_btn").show();
            }
        });
    });
});

function btn_delete(id)
{
	var csrf_token = '{{ csrf_token() }}';
	var modalAdd = bootstrap.Modal.getInstance(document.getElementById('modal_add'));
	Swal.fire({
		icon: 'warning',
		title: 'Are you sure ?',
		allowOutsideClick: false
	}).then((result) => {
		if (result.value)
		{
			$.ajax({
				url:"{{ route('deleteCats') }}",
				data: {id:id, '_token' : csrf_token},
				method : 'delete',
				success: function(msg){
					Swal.fire({
						icon : 'success',
					}).then(function() {
						modalAdd.hide();
						$('#data-cats').DataTable().ajax.reload();
					});
				}
			});
		}
	});
}
</script>
