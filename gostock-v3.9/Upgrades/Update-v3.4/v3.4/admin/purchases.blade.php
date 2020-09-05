@extends('admin.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
           {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> {{ trans('misc.purchases') }} ({{$data->total()}})
          </h4>

        </section>

        <!-- Main content -->
        <section class="content">

        	<div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">
                  		{{ trans('misc.purchases') }}
                  	</h3>
                </div><!-- /.box-header -->

                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
               <tbody>

               	@if( $data->total() !=  0 && $data->count() != 0 )
                   <tr>
                      <th class="active">ID</th>
                      <th class="active">{{ trans('misc.thumbnail') }}</th>
                      <th class="active">{{ trans('admin.title') }}</th>
                      <th class="active">{{ trans('misc.uploaded_by') }}</th>
                      <th class="active">{{ trans('misc.buyer') }}</th>
                      <th class="active">{{ trans('misc.email_buyer') }}</th>
                      <th class="active">{{ trans('admin.type') }}</th>
                      <th class="active">{{ trans('misc.license') }}</th>
                      <th class="active">{{ trans('misc.price') }}</th>
                      <th class="active">{{ trans('misc.earnings') }}</th>
                      <th class="active">{{ trans('admin.date') }}</th>
                    </tr><!-- /.TR -->


                  @foreach( $data as $purchase )

                    @php

                    if(null !== $purchase->images()) {

                      $image_photo = Storage::url(config('path.thumbnail').$purchase->images()->thumbnail);
                      $image_title = $purchase->images()->title;
                      $image_url   = url('photo', $purchase->images()->id);

                      $purchase_username = $purchase->user()->username;
                      $purchase_email = $purchase->user()->email;

                      $uploaded_by = $purchase->images()->user()->username;
                      $uploaded_by_url = url($uploaded_by);

                    } else {
                      $image_photo = null;
                      $image_title = trans('misc.not_available');
                      $image_url   = 'javascript:void(0);';

                      $_purchase_username = App\Models\User::whereId($purchase->user_id)->first();
                      $purchase_username = $_purchase_username->username;

                      $_purchase_email = App\Models\User::whereId($purchase->user_id)->first();
                      $purchase_email = $_purchase_email->email;

                      $uploaded_by = trans('misc.not_available');
                      $uploaded_by_url = 'javascript:void(0);';
                    }

                    switch ($purchase->type) {
              			case 'small':
              				$type          = trans('misc.small_photo');
              				break;
              			case 'medium':
              				$type          = trans('misc.medium_photo');
              				break;
              			case 'large':
              				$type          = trans('misc.large_photo');
              				break;
                    case 'vector':
                        $type          = trans('misc.vector_graphic');
                        break;
                      }

                      switch ($purchase->license) {
                			case 'regular':
                				$license          = trans('misc.regular');
                				break;
                			case 'extended':
                				$license          = trans('misc.extended');
                				break;
                        }

                    @endphp

                    <tr>
                      <td>{{ $purchase->id }}</td>
                      <td><img src="{{$image_photo}}" width="50" onerror="" /></td>
                      <td><a href="{{ $image_url }}" title="{{$image_title}}" target="_blank">{{ str_limit($image_title, 20, '...') }} <i class="fa fa-external-link-square"></i></a></td>
                      <td><a href="{{$uploaded_by_url}}" target="_blank">{{$uploaded_by}} <i class="fa fa-external-link-square"></i></a></td>
                      <td><a href="{{url($purchase_username)}}" target="_blank">{{$purchase_username}} <i class="fa fa-external-link-square"></i></a></td>
                      <td>{{ $purchase_email }}</td>
                      <td>{{ $type }}</td>
                      <td>{{$license}}</td>
                      <td>{{ App\Helper::amountFormat($purchase->price) }}</td>
                      <td>{{ App\Helper::amountFormatDecimal($purchase->earning_net_admin) }}</td>
                      <td>{{ date('d M, Y', strtotime($purchase->date)) }}</td>
                    </tr><!-- /.TR -->
                    @endforeach

                    @else
                    <hr />
                    	<h3 class="text-center no-found">{{ trans('misc.no_results_found') }}</h3>

                    @endif

                  </tbody>

                  </table>

                </div><!-- /.box-body -->
              </div><!-- /.box -->
              @if( $data->lastPage() > 1 )
             {{ $data->links() }}
             @endif
            </div>
          </div>

          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection
