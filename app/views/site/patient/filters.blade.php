<script type="text/javascript" src="{{asset('/js/patientdropdown.js')}}"></script>
<select class="multiselect_1" multiple="multiple">
	@if(in_array('scheduledyes',$filters))
        <option value="scheduledyes" class="scheduledyes" selected>Scheduled "Yes"</option>
    @else
        <option value="scheduledyes" class="scheduledyes">Scheduled "Yes"</option>
    @endif
    @if(in_array('scheduledno',$filters))
        <option value="scheduledno" class="scheduledno" selected>Scheduled "No"</option>
    @else
        <option value="scheduledno" class="scheduledno">Scheduled "No"</option>
    @endif
    @if(in_array('showedupyes',$filters))
        <option value="showedupyes" class="showedupyes" selected>Showed Up "Yes"</option>
    @else
        <option value="showedupyes" class="showedupyes">Showed Up "Yes"</option>
    @endif
    @if(in_array('showedupno',$filters))
        <option value="showedupno" class="showedupno" selected>Showed Up "No"</option>
    @else
        <option value="showedupno" class="showedupno">Showed Up "No"</option>
    @endif
    <optgroup label="Diagnoses">
    	@foreach(Auth::user()->practice->diagnosis() as $diagnosis)
            @if(in_array($diagnosis->name,$filters))
                <option value="{{ $diagnosis->name }}" selected> {{ $diagnosis->name }}</option>
            @else
                <option value="{{ $diagnosis->name }}"> {{ $diagnosis->name }}</option>
            @endif
    	@endforeach
    </optgroup>
	<optgroup label="Referral Source?">
	@foreach($doctors as $doctor)
				@if(in_array($doctor->id,$filters))
					<option value="{{ $doctor->id }}" selected> {{ $doctor->name }}</option>
				@else
					<option value="{{ $doctor->id }}"> {{ $doctor->name }}</option>
				@endif
	@endforeach
		</optgroup>
</select>

<script type="text/javascript">
	$(document).ready(function() {
		$('.multiselect_1').multiselect({
			nonSelectedText: 'Filters',
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
				clickFilters();
			}
		});
	});
</script>