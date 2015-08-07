@if(count($referralSources) != 0)
   <center>
    <table class="table table-bordered table-striped table-hover">
    <th>Referral Source Name</th>
    <th>Total Referrals</th>
    <th>Tasks</th>

      @foreach($referralSources as $referralSource)
          <tr class="clinicdetailsrow" style="cursor: pointer;">
             <td class="detailed" id="{{$referralSource->id}}"><a href="/clinic/details/id/{{$referralSource->id}}">{{$referralSource->name}}</a></td>
			 <td>{{ count($referralSource->cases) }}</td>
			 <td>{{ count($referralSource->referralOffice->tasks()->where('is_completed', '<>', true)->get()); }}</td>
          </tr>
      @endforeach
    </table>
    </center>
@else
    <center><h3>This user haven`t any added office.</h3></center>
@endif

<center>
    <ul class="pagination" id="paginationul"> <!-- magic of webwrapper of laravelpagination for ajax -->
        <?php
        $decoded_data = $referralSources;
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
                getClinicsByPage(nextpageid);
            }
        }
    });

    function getClinicsByPage(pagetoview)
    {//accessing by post Patietslist func
        $('.patientview').html(' ');
        var csrftoken = $('.csrf_token').val();
        $.ajax({
            type : "GET",
            url : "/clinic/allcliniclist",
            data : {'csrf_token':csrftoken, 'page':pagetoview},
            success: function(data){
                if (data)
                {

                    $('#allclinicview').html('');
                    $('#allclinicview').html(data);
                }
            }
        });
    }

    $('.clinicdetailsrow').click(function(elem)
    {
        var idtogo = $(this).find('.detailed').attr('id');

        window.location.href = '/clinic/details/id/'+idtogo;

    });

</script>