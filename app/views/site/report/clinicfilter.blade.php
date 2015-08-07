<script type="text/javascript" src="{{asset('/js/reportaddition.js')}}"></script>
<select class="multiselect_2" multiple="multiple">
	@foreach($clinics as $cliniccopy)
		<option id="{{ $cliniccopy->id }}" class="clinicfilter"> {{ $cliniccopy->name }}</option>
	@endforeach
</select>
<script type="text/javascript">
	$(document).ready(function() {
		$('.multiselect_2').multiselect({
			nonSelectedText: 'Office Location',
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
				clickClinicFilter();
			}
		});

	});
</script>