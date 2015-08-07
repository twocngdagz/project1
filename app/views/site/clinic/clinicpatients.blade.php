<?php $decoded_notes_data = json_decode($clinicpatients); ?>

@if(count($decoded_notes_data->data) != 0)   
<table class="table table-bordered table-striped" style="text-align:center">
    <th style="text-align:center">Patient</th><th style="text-align:center">Scheduled?</th><th style="text-align:center">Insurance</th><th style="text-align:center">Date</th>
    @foreach($decoded_notes_data->data as $patientcopy)
          <tr>
            <td>{{ Patient::findOrFail($patientcopy->patient_id)->name }}</td>
            <td>
            @if($patientcopy->is_scheduled == '1')
                Yes
            @else
                No
            @endif
            </td>
            <td>
            @if($patientcopy->insurance_id)
                {{ Insurance::find($patientcopy->insurance_id)->name }}
            @endif
            </td>
            <td>{{ $patientcopy->created_at }}</td>
          </tr>
    @endforeach
</table>
@else
    <div><hr></div>
    <center><h3>There no Patients for this Office!</h3></center>
    <div><hr></div>
@endif
<!--- Pagination --->
<center>
  <ul class="pagination" id="paginationulclinic"> <!-- magic of webwrapper of laravelpagination for ajax -->
        <?php
            $decoded_data = json_decode($clinicpatients);
            $paginationresult = '';
            $countpages = '';
            $atallresults = $decoded_data->total;
            if ($atallresults > $decoded_data->per_page)
            {
                $countpages = $atallresults / $decoded_data->per_page;
                if (is_float($countpages))
                { // count all pages
                    $countpages = (int) $countpages + 1;
                }
                if ($decoded_data->current_page == 1)
                { //setting up prev button 
                    $paginationresult = $paginationresult.'<li class="prev disabled" id="1"><span id="1">«</span></li>';
                } else
                {
                    $paginationresult = $paginationresult.'<li class="prev" id="'.($decoded_data->current_page - 1).'"><span id="'.($decoded_data->current_page - 1).'">«</span></li>';
                }
                
                for ($i = 1; $i < $countpages+1; $i++)
                { // doing link to pages
                    
                    if ($i == $decoded_data->current_page)
                    {
                        $paginationresult = $paginationresult.'<li class="page active" id="'.$i.'"><span id="'.$i.'">'.$i.'</span></li>';
                    } else
                    {
                        $paginationresult = $paginationresult.'<li class="page" id="'.$i.'"><span id="'.$i.'">'.$i.'</span></li>';
                    }
                }
                
                if ($decoded_data->current_page == $countpages)
                { //setting up next button
                    $paginationresult = $paginationresult.'<li class="next disabled page" id="'.$decoded_data->current_page.'"><span id="'.$decoded_data->current_page.'">»</span></li>';
                } else
                {
                    $paginationresult = $paginationresult.'<li class="next page" id="'.($decoded_data->current_page + 1).'"><span id="'.($decoded_data->current_page + 1).'">»</span></li>';
                }
            }
            echo $paginationresult;
        ?>
  </ul>
</center>



<script>
    $('#paginationulclinic').click(function(elem)
    {
        var nextpageid = $(elem.target).attr('id');
        var getparrent = $(elem.target).parent();
        if (!$(getparrent).hasClass('disabled'))
        {
            if (!$(getparrent).hasClass('active'))
            {
                  getPatientsByPage(nextpageid);
            }
        }
    });
    
    function getPatientsByPage(pagetoview)
    {//accessing by post Patietslist func
        $('#clinicpatientsarea').html(' ');
        var csrftoken = $('.csrf_token').val();
        var clinicid = $.trim($('#referral_source_id').val());
        $.ajax({
            type : "GET",
            url : "/clinic/patientslist",
            data : {'csrf_token':csrftoken, 'page':pagetoview, 'clinicid':clinicid},
            success: function(data){
                    if (data)
                    {
                        //~ clearNewPatientForm();
                        $('#clinicpatientsarea').html('');
                        $('#clinicpatientsarea').html(data);
                    }
                }  
        });
    }
</script>
<!--- /Pagination --->
