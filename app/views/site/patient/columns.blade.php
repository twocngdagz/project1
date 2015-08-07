<script type="text/javascript" src="{{asset('/js/patientdropdown.js')}}"></script>
<select class="multiselect_2" multiple="multiple">
@foreach($columns as $key => $status)
    @if($key != 'patientclm')
		<option value="{{ $key }}" class="{{ $key }}"
        @if($status != "false") 
            {{ "selected" }}
         @endif
         > {{ $columnsmessages[$key] }}</option>

    @endif
@endforeach

</select>
<script type="text/javascript">
	$(document).ready(function() {
		$('.multiselect_2').multiselect({
			nonSelectedText: 'Columns',
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
				clickColumns();
			}
		});

	});
</script>