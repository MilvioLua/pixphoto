<ul class="nav nav-pills nav-justified margin-bottom-20">
			<li>
				<!-- **** list-group-item **** -->	
		  <a href="{{ url('account') }}" class="list-group-item font-default myicon-right @if(Request::is('account'))active @endif"> 
		  	<i class="fa fa-pencil myicon-right"></i> {{ trans('users.account_settings') }} 
		  	</a> <!-- **** ./ list-group-item **** -->
			</li>
				
		  	<li>
		  		<!-- **** list-group-item **** -->	
		  <a href="{{ url('account/password') }}" class="list-group-item font-default @if(Request::is('account/password'))active @endif"> 
		  	<i class="fa fa-lock myicon-right"></i> {{ trans('auth.password') }} 
		  	</a> <!-- **** ./ list-group-item **** -->
		  	</li>
		</ul>