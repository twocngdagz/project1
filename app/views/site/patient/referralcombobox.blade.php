<script type="text/javascript" src="{{asset('/bootstrap/js/bootstrap-combobox.js')}}"></script>

<select class="form-control combobox" tabindex="2" name="inputreferrals" id="inputreferrals" value="">
			<option value="" disabled selected>Referral Source</option>
				@if(count($referralsource) != 0)
                    @foreach ($referralsource as $referrals)
                        @if($referrals->name==$doctorname)
                            <option value="{{ $referrals->id }}" selected>{{ $referrals->name }}</option>
                        @else
                            <option value="{{ $referrals->id }}">{{ $referrals->name }}</option>
                        @endif
                    @endforeach
                  @endif
				@if(count($all_activities) != 0)
                    @foreach ($all_activities as $referrals)
                        @if($referrals->campaign_name==$doctorname)
                            <option class="ref_activity" value="{{ $referrals->campaign_name }}" selected>{{ $referrals->campaign_name }}</option>
                        @else
                            <option class="ref_activity" value="{{ $referrals->campaign_name }}">{{ $referrals->campaign_name }}</option>
                        @endif
                    @endforeach
                  @endif
</select>


<script>
$(document).ready(function(){
		$('.combobox').combobox({menu: '<ul class="typeahead typeahead-long dropdown-menu addreff"></ul>'});
	});
</script>