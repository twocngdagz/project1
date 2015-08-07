
   @if(count($patients) != 0)
   <center>
    <table class="table table-bordered table-hover">
    <th class="patient-row-colored">ID#</th>
    @foreach($columns as $key => $status) 
        @if($key == 'patientclm')
                <th class="patient-row-colored">{{ $columnsmessages[$key] }}</th>
        @else
            @if($status != "false")
                @if ($key == 'valueclm')
                @else
                    <th class="patient-row-colored">{{ $columnsmessages[$key] }}</th>
                @endif
            @endif
        @endif
    @endforeach
        <?php $previous_patient = "";
              $previous_id = "";
              $counter = 2;
              $previous_color = "patient-row-colored";
              $next_color = "patient-row-no-color";
        ?>
      @foreach($patients as $oneitem)
          <tr class="userdetailsrow 
              <?php if (($previous_patient == $oneitem->name) && ($previous_id == $oneitem->id)) 
                {
                  echo($previous_color);
                } else { 
                  echo($next_color);
                  if ($next_color == 'patient-row-no-color')
                  {
                    $next_color = 'patient-row-colored';
                    $previous_color = 'patient-row-no-color';
                  } else {
                    $next_color = 'patient-row-no-color';
                    $previous_color = 'patient-row-colored';
                  }
                } 
              ?>" style="cursor: pointer;">
              @if ($previous_patient != $oneitem->name)
                  <td class="detailed" id="{{ $oneitem->id }}">{{ $oneitem->id }}</td>
                  <td><a href="/user/patient/id/{{ $oneitem->id }}">{{ $oneitem->name }}</a></td>
              @else
                @if ($previous_id == $oneitem->id)
                  <td></td>
                  <td></td>
                @else
                  <td class="detailed" id="{{ $oneitem->id }}">{{ $oneitem->id }}</td>
                  <td><a href="/user/patient/id/{{ $oneitem->id }}">{{ $oneitem->name }}</a></td>
                @endif  
              @endif
              @if($columns['dateinitiatedclm'] != "false")
                <td>{{ $oneitem->created_at }}</td>
              @endif
              @if($columns['findusclm'] != "false")
                @if ($oneitem->activity_id)
                  <td>{{ Activities::find($oneitem->activity_id)->campaign_name; }}</td>
                @else
                    <td></td>
                @endif
              @endif
              @if($columns['insuranceclm'] != "false")
	              @if($oneitem->insurance_id)
	                <td>{{ Insurance::find($oneitem->cases->first()->insurance_id)->name; }}</td>
	              @else
	                <td> </td>
	              @endif
              @endif
              @if($columns['isscheduled'] != "false")
                  <td>
                    @if($oneitem->is_scheduled)
                        Yes
                    @else
                        No
                    @endif
                  </td>
              @endif
              @if($columns['showedupclm'] != "false")
                  <td>
                      @if($oneitem->first_appointment)
                          Yes
                      @else
                          No
                      @endif
                  </td>
              @endif
              @if($columns['reasonclm'] != "false")
                <td>{{ $oneitem->reasonnotscheduled_id ?  ReasonNotScheduled::find($oneitem->reasonnotscheduled_id)->description : ""}}</td>
              @endif
              @if($columns['referralclm'] != "false")
                    @if(ReferralSource::find($oneitem->referralsource_id))
                        <td>{{ ReferralSource::find($oneitem->referralsource_id)->name; }}</td>
                    @else
                        <td>{{ Activities::find($oneitem->activity_id)->campaign_name; }}</td>
                    @endif
              @endif
              @if($columns['diagnosisclm'] != "false")
                @if ($oneitem->diagnosis_id)
                    <?php $str_diagnoses_name=Diagnoses::find($oneitem->diagnosis_id); ?>
                    @if($str_diagnoses_name)
                        <td>{{ $str_diagnoses_name->name; }}</td>
                    @else
                        <td> </td>
                    @endif
                @else
                    <td></td>
                @endif
              @endif
              @if($columns['clinicclm'] != "false")
                <td>{{ $oneitem->officeLocation->name }}</td>
              @endif
              @if($columns['phoneclm'] != "false")
                <td>{{ $oneitem->phone }}</td>
              @endif
          </tr>
        <?php
            $previous_patient = $oneitem->name;
            $previous_id = $oneitem->id;
            $counter++
        ?>
      @endforeach
    </table>
    </center>
   @else
    <center><h3>This office does not have any patients added yet.</h3></center>
   @endif
<!--- Pagination --->
<center>
  <ul class="pagination" id="paginationul"> <!-- magic of webwrapper of laravelpagination for ajax -->
        <?php
            $decoded_data = $patients;
            $paginationresult = '';
            $countpages = '';
            $atallresults = $decoded_data->getTotal();
            if ($atallresults > $decoded_data->getPerPage())
            {
                $countpages = $atallresults / $decoded_data->getPerPage();
                if (is_float($countpages))
                { // count all pages
                    $countpages = (int) $countpages + 1;
                }
                if ($decoded_data->getCurrentPage() == 1)
                { //setting up prev button 
                    $paginationresult = $paginationresult.'<li class="prev disabled" id="1"><span id="1">«</span></li>';
                } else
                {
                    $paginationresult = $paginationresult.'<li class="prev" id="'.($decoded_data->getCurrentPage() - 1).'"><span id="'.($decoded_data->getCurrentPage() - 1).'">«</span></li>';
                }
                
                for ($i = 1; $i < $countpages+1; $i++)
                { // doing link to pages
                    
                    if ($i == $decoded_data->getCurrentPage())
                    {
                        $paginationresult = $paginationresult.'<li class="page active" id="'.$i.'"><span id="'.$i.'">'.$i.'</span></li>';
                    } else
                    {
                        $paginationresult = $paginationresult.'<li class="page" id="'.$i.'"><span id="'.$i.'">'.$i.'</span></li>';
                    }
                }
                
                if ($decoded_data->getCurrentPage() == $countpages)
                { //setting up next button
                    $paginationresult = $paginationresult.'<li class="next disabled page" id="'.$decoded_data->getCurrentPage().'"><span id="'.$decoded_data->getCurrentPage().'">»</span></li>';
                } else
                {
                    $paginationresult = $paginationresult.'<li class="next page" id="'.($decoded_data->getCurrentPage() + 1).'"><span id="'.($decoded_data->getCurrentPage() + 1).'">»</span></li>';
                }
            }
            echo $paginationresult;
        ?>
  </ul>
</center>


<script>
    $('#paginationul').click(function(elem)
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
        $('.patientview').html(' ');
        var csrftoken = $('.csrf_token').val();
        $.ajax({
            type : "GET",
            url : "/patient/patientslist",
            data : {'csrf_token':csrftoken, 'page':pagetoview},
            success: function(data){
                    if (data)
                    {
                       
                        $('.patientview').html('');
                        $('.patientview').html(data);
                    }
                }  
        });
    }
    
    $('.userdetailsrow').click(function(elem)
    {
        var idtogo = $(this).find('.detailed').attr('id');
    
        window.location.href = '/user/patient/id/'+idtogo;
 
    });
    
</script>
<!--- /Pagination --->

