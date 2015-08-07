<script type="text/javascript" src="{{asset('/js/reportaddition.js')}}"></script>
<select class="multiselect_1" multiple="multiple">
	<optgroup label="Clinic">
@foreach($clinics as $cliniccopy)
		<option id="{{ $cliniccopy->id }}" class="clinicreffilter"> {{ $cliniccopy->name }}</option>
@endforeach
	</optgroup>
	<optgroup label="Dr.">
@foreach($referrals as $referralcopy)
	{{--@if($cliniccopy->id == $referralcopy->practice_location_id)--}}
		<option id="{{ $referralcopy->id }}" class="referralfilter"> {{ $referralcopy->name }}</option>
	{{--@endif--}}
@endforeach
	</optgroup>
</select>

<script type="text/javascript">
	$(document).ready(function() {
		$('.multiselect_1').multiselect({
			nonSelectedText: 'Referral Sources',
			numberDisplayed: 0,
			maxHeight: 400,
			includeSelectAllOption: true,
            			buttonText: function(options, select) {
                                    if (options.length == 0) {
                                        return this.nonSelectedText + ' <b class="caret"></b>';
                                    }
                                    else {
                                        if (options.length > this.numberDisplayed) {
                                            return this.nonSelectedText + ' ('+ options.length + ' ' + this.nSelectedText + ')' + ' <b class="caret"></b>';
                                        }
                                    }
                                },
			onChange: function(element, checked) {
				clickReferralsource();
			}
		});
	});
</script>