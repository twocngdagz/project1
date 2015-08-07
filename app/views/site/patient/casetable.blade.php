<table class="table table-bordered table-striped table-hover">
    <thead>
        <th>Date Initiated</th>
        <th>Diagnosis</th>
        <th>Referral Source</th>
        <th>How did you Find Us</th>
        <th>Scheduled</th>
        <th>Free Screening Attended</th>
        <th>First Appointment Attended</th>
    </thead>
    <tbody>
        @foreach ($cases as $case)
            <tr style="cursor: pointer;" id="{{$case->id}}">
                <td>{{$case->created_at->format('m/d/Y');}}</td>
                @if ($case->diagnosis)
                    <td>{{$case->diagnosis->name}}</td>
                @else
                    <td></td>
                @endif
                @if ($case->referralSource)
                    <td>{{$case->referralSource->name}}</td>
                @else
                    <td></td>
                @endif
                @if ($case->activity)
                    <td>{{$case->activity->campaign_name}}</td>
                @else
                    <td></td>
                @endif
                <td>{{$case->is_scheduled ? 'Yes' : 'No'}}</td>
                @if ($case->free_evaluation)
                    <td id="case-evaluation-{{$case->id}}">{{$case->free_evaluation->format('m/d/Y')}}</td>
                @else
                    <td style="text-align: center;" id="case-evaluation-{{$case->id}}"><button class="btn btn-success btn-xs patient-checkin-evaluation" data-id="{{$case->id}}">Checkin</button></td>
                @endif
                @if ($case->first_appointment)
                    <td id="case-appointment-{{$case->id}}">{{$case->first_appointment->format('m/d/Y')}}</td>
                @else
                    <td style="text-align: center;" id="case-appointment-{{$case->id}}"><button class="btn btn-success btn-xs patient-checkin-appointment" data-id="{{$case->id}}">Checkin</button></td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>

