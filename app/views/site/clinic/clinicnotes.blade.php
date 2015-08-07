
<?php $decoded_notes_data = json_decode($clinicnotes) ?>


@if(count(json_decode($clinicnotes)->data) != 0)   
    @foreach($decoded_notes_data->data as $notecopy)
         <div style="height:20px"><hr></div>
         <?php
            $date =  date_create($notecopy->created_at);
            ?>
        <b>{{ date_format($date, "l, jS F") }}</b><br>
        Posted by {{ User::find($notecopy->owner_id)->name }}
        <div style="height:10px;"></div>
        <p>{{ $notecopy->description }}</p>
    @endforeach
@else
    <div><hr></div>
    <center><h3>No Notes</h3></center>
    <div><hr></div>
@endif

<!--- Pagination --->
<center>
  <ul class="pagination" id="paginationulnotes"> <!-- magic of webwrapper of laravelpagination for ajax -->
        <?php
            $decoded_data = json_decode($clinicnotes);
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
    $('#paginationulnotes').click(function(elem)
    {
        var nextpageid = $(elem.target).attr('id');
        var getparrent = $(elem.target).parent();
        if (!$(getparrent).hasClass('disabled'))
        {
            if (!$(getparrent).hasClass('active'))
            {
                  getNotesByPage(nextpageid);
            }
        }
    });
    
    function getNotesByPage(pagetoview)
    {//accessing by post Patietslist func
        $('#notesalreadyadded').html(' ');
        var csrftoken = $('.csrf_token').val();
        $.ajax({
            type : "GET",
            url : "/clinic/noteslist",
            data : {'csrf_token':csrftoken, 'page':pagetoview},
            success: function(data){
                    if (data)
                    {
                        
                        $('#notesalreadyadded').html('');
                        $('#notesalreadyadded').html(data);
                    }
                }  
        });
    }
</script>
<!--- /Pagination --->
