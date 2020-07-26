<table class="table-hover table-striped table-bordered" id="table-student-info">

    <thead>
        <tr class="text-align">
            <th style="text-align: center; width: 10%">StudentID</th>
            <th style="text-align: center; width: 20%">Student Name</th>
            <th style="text-align: center; width: 15%">Gender</th>
            <th style="text-align: center; width: 15%">Phone</th>
            <th style="text-align: center; width: 10%">Programe</th>
            <th style="text-align: center; width: 10%">Batch</th>
            <th style="text-align: center; width: 10%">Group</th>
            <th style="text-align: center; width: 15%">DOB</th>
        </tr>
    </thead>
    <tbody class="text-center">
		@foreach($studentReports as $key => $stReport)

            <tr >
                <td>{{ $stReport->student_id}}</td>
                
                <td>{{ $stReport->name}}</td>
                <td>{{ $stReport->sex}}</td>
                <td><b>{{ $stReport->phone}}</b></td>
                <td>{{ $stReport->programe}}</td>
                <td>{{ $stReport->batch}}</td>
                <td>{{ $stReport->group}}</td>
                <td>{{ $stReport->dob}}</td>

            </tr>
        @endforeach
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function(){
        $('#table-student-info').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
        });
    })
</script> 