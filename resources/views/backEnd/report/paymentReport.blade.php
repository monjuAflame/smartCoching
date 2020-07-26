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
<table class="table table-hover table-striped table-bordered" id="table-payment-info">
    <thead>
        <tr class="text-center">
            <th>S.ID</th>
            <th>Student Name</th>
            <th>Fee Type</th>
            <th>Programe</th>
            <th>Level</th>
            <th>Course Fee</th>
            <th>Discount</th>
            <th>Student Fee</th>
            <th>Total Paid</th>
            <th>Amount Due</th>
        </tr>
    </thead>
    <tbody>
        
        @foreach($studentFee as $fee)
            <tr class="text-center {{ $fee->studentAmount-$transact->where('s_fee_id',$fee->s_fee_id)->sum('paid')==0 ? 'bggreen' : 'bgred' }}">
                <td>{{$fee->student_id}}</td>
                <td>{{$fee->studentName}}</td>
                <td>{{$fee->fee_type}}</td>
                <td>{{$fee->programe}}</td>
                <td>{{$fee->level}}</td>
                <td class="indigo">$ {{number_format($fee->courseFee,2)}}</td>
                <td class="{{ $fee->discount==0 ? 'red' : 'green' }}"><b>{{$fee->discount}}%</b></td>
                <td class="indigo">$ {{number_format($fee->studentAmount,2)}}</td>
                <td class="green"><b>$ {{ number_format($transact->where('s_fee_id',$fee->s_fee_id)->sum('paid'),2) }}</b></td>
                <td class="{{ $fee->studentAmount-$transact->where('s_fee_id',$fee->s_fee_id)->sum('paid')==0 ? 'green' : 'red' }}"><b>$ {{ number_format($fee->studentAmount - $transact->where('s_fee_id',$fee->s_fee_id)->sum('paid'),2)}}</b></td>
            </tr>
        @endforeach
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function(){
        $('#table-payment-info').DataTable({
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