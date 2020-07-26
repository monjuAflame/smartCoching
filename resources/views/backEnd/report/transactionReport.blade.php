<style type="text/css">
    .academic-details{
        width: 440px;
    }
    #table-class-info{
        width: 100%;
    }
    table  tbody > tr > td {
        vertical-align: middle;
        text-align: center;
    }
    table thead tr > th {
        text-align: center;
    }
</style>
<div class="col-md-3">
    
    
</div>
<table class="table-hover table-striped table-bordered" id="table-student-info"></h3>
    <thead>
        <tr class="text-align">
            <th style="text-align: center; width: 5%">No</th>
            <th style="text-align: center; width: 20%">Student Name</th>
            <th style="text-align: center; width: 5%">St.ID</th>
            <th style="text-align: center; width: 5%">Cashier</th>
            <th style="text-align: center; width: 15%">Transacted Date</th>
            <th style="text-align: center; width: 10%">School Fee</th>
            <th style="text-align: center; width: 10%">Discount</th>
            <th style="text-align: center; width: 10%">Student Fee</th>
            <th style="text-align: center; width: 10%">Paid</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1
        @endphp
        @foreach($transactions as $transaction)
            <tr>
                <th style="text-align: center;">{{$no++}}</th>
                <th style="text-align: center;">{{ $transaction->studentName." ".$transaction->nick_name }}</th>
                <th style="text-align: center;">{{$transaction->student_id}}</th>
                <th style="text-align: center; ">{{$transaction->cashier}}</th>
                <th style="text-align: center; ">{{$transaction->transact_date}}</th>
                <th style="text-align: center; ">{{$transaction->course_fee}}</th>
                <th style="text-align: center;">{{$transaction->discount}}%</th>
                <th style="text-align: center;">{{$transaction->student_fee}}</th>
                <th style="text-align: center; ">{{$transaction->paid}}</th>
            </tr>
        @endforeach
    </tbody>
</table>
<script type="text/javascript">
    $(document).ready(function(){
        $('#table-student-info').DataTable({
            dom: 'Bfrtip',
            buttons:[
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
        });
    })
</script>