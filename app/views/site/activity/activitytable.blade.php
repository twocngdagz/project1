
   <?php setlocale(LC_MONETARY, 'en_US');?>
   @if(count($patients) != 0)
   <center>
    <table class="table table-bordered table-striped table-hover">
    <!--- table header expanding-->
    <th>Date</th>
    <th>Activity Type </th>
    <th>Source / Campaign</th>
    <th>Conversions</th>
    <th>Revenue</th>
    <th>Cost</th>
    <th>Description</th>
    <th></th>
    <th></th>

    <!-- table header expanding-->
      @foreach($patients as $oneitem)
          <tr class="userdetailsrow" style="cursor: pointer;">
              <td>{{ date("m/d/Y", strtotime($oneitem->created_at)); }}</td>
			  <td>{{ ActivityTypes::where('id','=',$oneitem->activity_type_id)->where('practice_id',Auth::user()->practice->id)->first()->name; }}</td>
			  <td>{{ $oneitem->campaign_name }}</td>
			  <td style="text-align:right;">{{ Auth::user()->practice->cases()->where('activity_id',$oneitem->id)->converted()->count(); }}</td>
			  <td style="text-align:right;">$@m_format($oneitem->revenue)</td>
			  <td style="text-align:right;">$@m_format($oneitem->cost)</td>
			  <td>{{ $oneitem->description }}</td>
              <?php
              $oneitem->campaign_name = str_replace("'", "&apos;", $oneitem->campaign_name);
              ?>
              <td><a href="#" data-toggle="modal" data-target="#activity-edit" data-activity='{{$oneitem->toJson()}}'>edit</a></td>
              <td><a href="#" data-toggle="modal" data-target="#activity-delete" data-activity-id="{{$oneitem->id}}" data-name="{{ $oneitem->campaign_name }}">delete</a></td>
			  
			  
          </tr>
      @endforeach
    </table>
    </center>
   @else
    <center><h3>This office haven`t any activities.</h3></center>
   @endif
   <!-- Modal -->
   <div class="modal fade" id="activity-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog modal-lg">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel"></h4>
               </div>
               <div class="modal-body">
                   <div class="row btn-group btn-group-justified">
                       <div class="col-md-6">
                           <input type="text" tabindex="1" class="form-control" placeholder="Campaign name" name="campaign_name" id="campaign_name">
                            <input type="hidden" name="activity_id" id="activity_id">
                       </div>

                       <div id="insert_combobox" class="col-md-4">
                           <select class="form-control combobox" tabindex="2" name="activity_type" id="activity_type" value="">
                               <option value="" disabled>Activity Type</option>
                               @if(count(Auth::user()->practice->activityTypes) != 0)
                                    @foreach (Auth::user()->practice->activityTypes as $activity)
                                        <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                                    @endforeach
                               @endif
                           </select>

                       </div>
                   </div>

                   <br>
                   <div class="row  btn-group btn-group-justified">
                       <div class="col-md-6">
                           <div class="input-group">
                               <span class="input-group-addon">$</span>
                               <input type="text" tabindex="3" class="form-control" placeholder="Cost" name="cost" id="cost">
                           </div>

                       </div>

                       <div class="col-md-6">
                           <div data-date-format="mm/dd/yy" data-date="" id="dp5" class="input-append date datefirst ">
                               <input type="text" tabindex="4" id="date_created" value="" size="16" placeholder="Select date" class="span2 form-control">
                                <span class="add-on">
                                    <i class="icon-calendar"></i>
                                </span>
                           </div>

                       </div>
                   </div><br>


                   <!-- Description -->
                   <div class="row  btn-group btn-group-justified">
                       <div class="col-md-12">
                           <textarea id="description" tabindex="5" name="description" rows="4" class="form-control" placeholder="Description"></textarea>
                       </div>

                   </div>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                   <button type="button" class="btn btn-primary" id="activity-update-button">Update</button>
               </div>
           </div>
       </div>
   </div>
   <!-- Modal -->
   <div class="modal fade" id="activity-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
               </div>
               <div class="modal-body">

               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                   <button type="button" class="btn btn-danger" id="activity-delete-button">Delete</button>
               </div>
           </div>
       </div>
   </div>
<!--- Pagination -->
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
    $('#activity-delete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var name = button.data('name') // Extract info from data-* attributes
        var activity_id = button.data('activity-id');
        var modal = $(this)
        modal.find('.modal-body').text('Are you sure you want to delete ' + name + '?');
        $('#activity-delete-button').data('activity_id',activity_id);
    });

    $('.modal#activity-edit').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var activity = button.data('activity') // Extract info from data-* attributes
        var date_created = $('#dp5').datepicker({
            format: 'mm/dd/yyyy'
        }).on('changeDate', function(ev) {
            date_created.hide();
        }).data('datepicker');
        if (activity)
        {
            var endDate = new Date(activity.created_at);
            date_created.update(endDate.addDays(1));
            $('#myModalLabel').text('Editing ' + activity.campaign_name);
            $('#date_created').val();
            $('#activity_id').val(activity.id);
            $('#campaign_name').val(activity.campaign_name);
            $('#cost').val(activity.cost);
            $('#description').val(activity.description);
            $('#activity_type').val(activity.activity_type_id);
        }
    });

    $('#activity-delete-button').click(function (e) {
        e.preventDefault();
        $.ajax({
            type : "POST",
            url : "/activity/delete",
            data : {'csrf_token':$('.csrf_token').val(), 'activity_id':$(this).data('activity_id')},
            success: function(data){
                if (data == 'success')
                {
                    window.location.href = '/user/activity';
                } else {
                    $('#activity-delete').modal('hide');
                    $.growl({ title: '<strong>Error:</strong> ', message: data
                    },{
                        type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                        placement: { from: 'top', align: 'right' },
                        delay: 0
                    });
                }
            }
        });
    });

    $('#activity-update-button').click(function (e) {
        e.preventDefault();
        $.ajax({
            type : "POST",
            url : "/activity/update",
            data : {
                'csrf_token':$('.csrf_token').val(),
                'activity_id':$('#activity_id').val(),
                'campaign_name': $('#campaign_name').val(),
                'activity_type_id': $('#activity_type').val(),
                'cost': $('#cost').val(),
                'description': $('#description').val(),
                'created_at': $('#date_created').val()
            },
            success: function(data){
                if (data == 'success')
                {
                    window.location.href = '/user/activity';
                } else {
                    $.growl({ title: '<strong>Error:</strong> ', message: data
                    },{
                        type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                        placement: { from: 'top', align: 'right' },
                    });
                }
            }
        });
    });

    $('#paginationul').click(function(elem)
    {
        var nextpageid = $(elem.target).attr('id');
        var getparrent = $(elem.target).parent();
        
        if (!$(getparrent).hasClass('disabled'))
        {
            if (!$(getparrent).hasClass('active'))
            {
                  getActivitiesByPage(nextpageid);
            }
        }
    });
    
    function getActivitiesByPage(pagetoview)
    {//accessing by post Activity List func
        $('.activityview').html(' ');
        var csrftoken = $('.csrf_token').val();
        $.ajax({
            type : "GET",
            url : "/activity/activitylist",
            data : {'csrf_token':csrftoken, 'page':pagetoview},
            success: function(data){
                    if (data)
                    {
                       
                        $('.activityview').html('');
                        $('.activityview').html(data);
                    }
                }  
        });
    }
    
    
</script>
<!--- /Pagination --->

