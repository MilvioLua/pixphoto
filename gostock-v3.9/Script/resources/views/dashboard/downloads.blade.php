@extends('dashboard.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
           {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> {{ trans('misc.downloads') }} ({{$data->total()}})
          </h4>

        </section>

        <!-- Main content -->
        <section class="content">

        	<div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">
                  		{{ trans('misc.downloads') }}
                  	</h3>
                </div><!-- /.box-header -->

                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
               <tbody>

               	@if ($data->total() !=  0 && $data->count() != 0)
                   <tr>
                      <th class="active">ID</th>
                      <th class="active">{{ trans('misc.thumbnail') }}</th>
                      <th class="active">{{ trans('admin.title') }}</th>
                      <th class="active">{{ trans('admin.date') }}</th>
                      <th class="active">{{ trans('admin.actions') }}</th>
                    </tr><!-- /.TR -->


                  @foreach ($data as $downloads)

                    @php

                    $image_photo = Storage::url(config('path.thumbnail').$downloads->thumbnail);
                    $image_title = $downloads->title;
                    $image_url   = url('photo', $downloads->id);
                    $download_url = url('download/stock', $downloads->token_id);

                    @endphp

                    <tr>
                      <td>{{ $downloads->id }}</td>
                      <td><img src="{{$image_photo}}" width="50" onerror="" /></td>
                      <td><a href="{{ $image_url }}" title="{{$image_title}}" target="_blank">{{ str_limit($image_title, 25, '...') }} <i class="fa fa-external-link-square"></i></a></td>
                      <td>{{ date('d M, Y', strtotime($downloads->dateDownload)) }}</td>
                      <td>
                        @if ($image_photo == null)
                          <em>{{$image_title}}</em>
                        @else
                        <form method="POST" action="{{$download_url}}" accept-charset="UTF-8" class="displayInline">
                          @csrf
                          <input name="downloadAgain" type="hidden" value="true">
                          <input name="type" type="hidden" value="{{$downloads->size ?: 'large'}}">
                          <button type="submit" class="btn btn-success btn-sm padding-btn">
                            <i class="fa fa-cloud-download myicon-right"></i> {{ trans('misc.download') }}
                          </button>
                        </form>
                      @endif
                        </td>
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

              @if ( $data->lastPage() > 1 )
             {{ $data->links() }}
             @endif

            </div>
          </div>

          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection
