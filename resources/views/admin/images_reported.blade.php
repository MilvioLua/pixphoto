@extends('admin.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
           {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> {{ trans('admin.images_reported') }} ({{$data->count()}})
          </h4>

        </section>

        <!-- Main content -->
        <section class="content">

		    @if(Session::has('success_message'))
		    <div class="alert alert-success">
		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
								</button>
		       <i class="fa fa-check margin-separator"></i> {{ Session::get('success_message') }}
		    </div>
		@endif

        	<div class="row">
            <div class="col-xs-12">
              <div class="box">

                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
               <tbody>

               	@if( $data->count() !=  0 )
                   <tr>
                      <th class="active">ID</th>
                      <th class="active">{{ trans('admin.report_by') }}</th>
                      <th class="active">{{ trans('admin.reported') }}</th>
                      <th class="active">{{ trans('admin.reason') }}</th>
                      <th class="active">{{ trans('admin.date') }}</th>
                      <th class="active">{{ trans('admin.actions') }}</th>
                    </tr>

                  @foreach( $data as $report )
                    <tr>
                      <td>{{ $report->id }}</td>
                      <td><a href="{{ url($report->user()->username) }}" target="_blank">{{ $report->user()->username }} <i class="fa fa-external-link-square"></i></a></td>
                      <td><a href="{{ url('photo', $report->image()->id) }}" target="_blank">{{ str_limit($report->image()->title, 10, '...') }} <i class="fa fa-external-link-square"></i></a></td>

                      <?php if( $report->reason == 'copyright' ) {
								$reason = trans('admin.copyright');
                      		} elseif( $report->reason == 'privacy_issue' ) {
								$reason = trans('admin.privacy_issue');
                      		} else if( $report->reason == 'violent_sexual_content' ) {
								$reason = trans('admin.violent_sexual_content');
                      		}

                      	?>

                      <td>{{ $reason }}</td>

                      <td>{{ App\Helper::formatDate($report->created_at) }}</td>
                      <td>


                 {!! Form::open([
			            'method' => 'POST',
			            'url' => 'panel/admin/images-reported',
			            'class' => 'displayInline'
				        ]) !!}
				     {!! Form::hidden('id',$report->id ); !!}
	        {!! Form::submit(trans('admin.delete_report'), ['class' => 'btn btn-danger btn-xs padding-btn actionDelete']) !!}

	        	{!! Form::close() !!}

                      		</td>

                    </tr><!-- /.TR -->
                    @endforeach

                    @else
                    	<h3 class="text-center no-found">{{ trans('misc.no_results_found') }}</h3>
                    @endif

                  </tbody>


                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div>

          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection

@section('javascript')

<script type="text/javascript">

$(".actionDelete").click(function(e) {
   	e.preventDefault();

   	var element = $(this);
	var url     = element.attr('href');
	var form    = $(element).parents('form');

	element.blur();

	swal(
		{   title: "{{trans('misc.delete_confirm')}}",
		  type: "warning",
		  showLoaderOnConfirm: true,
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		   confirmButtonText: "{{trans('misc.yes_confirm')}}",
		   cancelButtonText: "{{trans('misc.cancel_confirm')}}",
		    closeOnConfirm: false,
		    },
		    function(isConfirm){
		    	 if (isConfirm) {
		    	 	form.submit();
		    	 	}
		    	 });


		 });
</script>
@endsection
