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
            <th>Transact ID</th>
            <th>Transact Date</th>
            <th>Fees</th>
            <th>Cashier</th>
            <th>Student Name(ID)</th>
            <th>Fee Type</th>
            <th>Paid</th>
        </tr>
    </thead>
    <tbody>
        
        @foreach($transactions as $transaction)
            <tr class="text-center">
                <td>{{ $transaction->transact_id }}</td>
                <td><b>{{ $transaction->transact_date }}</b></td>
                <td>{{ $transaction->feeType }}</td>
                <td>{{ $transaction->cashier }}</td>
                <td>{{ $transaction->studentName }} <b>({{ $transaction->student_id }})</b></td>
                <td>{{ $transaction->fee_type }}</td>
                <td class="green"><b>$ {{ number_format($transaction->paid,2) }}</b></td>
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