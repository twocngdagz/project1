<script type="text/javascript" src="{{asset('/bootstrap/js/bootstrap-combobox.js')}}"></script>

           <select class="form-control combobox" tabindex="2" name="inputreferrals" id="inputreferrals" value="">
                <option value="" disabled selected>Activity Type</option>
                @foreach (Auth::user()->practice->activityTypes as $activity)
                    @if($activity->name==$activityname)
                        <option value="{{ $activity->id }}" selected>{{ $activity->name }}</option>
                    @else
                        <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                    @endif
                @endforeach
		   </select>



<script>
$(document).ready(function(){
		$('.combobox').combobox({menu: '<ul class="typeahead typeahead-long dropdown-menu addact"></ul>'});
	});
</script>