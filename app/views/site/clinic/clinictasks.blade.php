
@if(count($clinictasks) != 0)   
    @foreach($clinictasks as $taskcopy)
        <div style="">
        <div class="btn-group" data-trigger="hover|click" data-toggle="tooltip" title="{{ $taskcopy->description }}"><label>&nbsp;<input class="waitingdonechecked" type="checkbox" id="{{ $taskcopy->id }}"> {{ $taskcopy->title }} </label></div>
        </div>
    @endforeach
@else
    <div style=""><hr></div>
    <center><h4>There no tasks for this office!</h4></center>
    <div><hr></div>
@endif

<script>
 // checking task going to ajax // change event
    $('input').change(function()
    {
        var taskid = $(this).attr('id');
        var csrftoken = $('.csrf_token').val();
        $.ajax({
            type: "POST",
            url:    "/clinic/taskcompleted",
            data: {'csrf_token':csrftoken, 'tasktocomplete':taskid},
            success: function(data)
            {
                if (data == 'success')
                {
                    getTasksForUpdate();
                }
            }
        });

    });
    
    function getTasksForUpdate()
    {
        $("#taskarea").html(' ');
        var csrftoken = $('.csrf_token').val();
        var clinicid = $.trim($('#clinic_id').val());
        $.ajax({
            type: "POST",
            url:    "/clinic/taskslist",
            data: {'csrf_token':csrftoken,'clinicid':clinicid},
            success: function(data)
            {
                if (data)
                {
                    $('#taskarea').html(' ');
                    $('#taskarea').html(data);
                }
            }
        });
    }
</script>

