@extends('frontEnd.layouts.app')


@section('content')
 <div class="container">
   <div class="row content-justify-center">
     <div class="col-md-12">
       <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  
                  <h4>
                    <i class="fas fa-globe"></i> Smart Coching Org.
                    <small class="float-right">Join Date: 
                      <span class="badge badge-primary badge-pill">
                      {{ $student->dateregistered }}
                    </span>
                    </small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                
                  <address>
                    <strong>Smart Coching</strong><br>
                    795 Moutain Tower<br>
                    Chattogram, Agrabad, CA 94107<br>
                    Phone: (+880) 123-5432<br>
                    Email: info@smartcoching.com
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <strong>Student Details</strong>
                  
                    <img src="{{ asset('img/student/')}}/{{ $student->photo }}" style="height: 100px;width: 80px;margin: 10% 6% 0 0;float: left;" alt="User Image">
             
                  <address>
                    <strong>
                      <span class="badge badge-primary badge-pill">
                      {{$student->name.' '.$student->nick_name}}
                      </span>
                    </strong><br>
                    {{ $student->current_address.', '.$student->post_office }}<br>
                    {{ $student->village.', '.$student->updistrict.', '.$student->district }}<br>
                    Phone: <span class="badge badge-primary badge-pill">{{ $student->phone }}</span><br>
                    Email: <span class="badge badge-primary badge-pill">{{ $student->email }}</span>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Student ID: <span class="badge badge-primary badge-pill">{{ $student->student_id }}</span></b><br>
             
                  <b>Course Attend:</b> 
                      <span class="badge badge-primary badge-pill">
                        {{ $status->count() }}
                      </span><br>
                  <b>Course Complete:</b> 
                   @php
                    $i = 0;
                   @endphp
                    @foreach($sfees as $sfee)
                      @if($sfee->studentFee==$sfee->total_paid)
                      <span class="badge badge-primary badge-pill">
                      {{$i+1}}
                    </span>
                      @endif
                    @endforeach
                  <br>
                  <b>Course Running:</b>
                    @php
                    $c = 0;
                   @endphp
                    @foreach($sfees as $sfee)
                      @if($sfee->studentFee!=$sfee->total_paid)
                      <span class="badge badge-primary badge-pill">
                      {{$c+1}}</span>
                      @endif
                    @endforeach
                  <br>
                  <b>Last Payment:</b> 
                      <span class="badge badge-primary badge-pill">
                  {{$ltransaction->transact_date}}</span><br>
              
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped text-center">
                    <thead>
                    <tr>
                      <th>No.</th>
                      <th>Programe</th>
                      <th>Level</th>
                      <th>Course Fee</th>
                      <th>Discount</th>
                      <th>Student Fee</th>
                      <th>Status</th>
                      <th>Print</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sfees as $key => $sfee)
                      <tr>
                        <td>{{$key+1}}</td>
                        <td>
                          <span class="badge badge-primary badge-pill">
                          {{ $sfee->programe }}
                        </span>
                        </td>
                        <td>
                          <span class="badge badge-success badge-pill">
                            {{ $sfee->levelDescription }} (  {{ $sfee->level }}  )
                          </span>
                        </td>
                        <td>$ {{ number_format($sfee->courseFee,2) }}</td>
                        <td>% {{ number_format($sfee->discount) }}</td>
                        <td>$ {{ number_format($sfee->studentFee,2) }}</td>
                        <td>
                          
                            @if($sfee->studentFee==$sfee->total_paid)
                            <span class="badge badge-success badge-pill">
                              <i class="fa fa-success"></i>Compelete
                            </span>
                            @else
                              <span class="badge badge-danger badge-pill">
                                <i class="fa fa-danger"></i>$ {{ number_format($sfee->studentFee-$sfee->total_paid,2)}} due
                              </span>
                            @endif
                        </td>
                        <td>
                          <a href="{{ route('print',$sfee->s_fee_id) }}" target="_blank">
                            <i class="fa indigo fa-print"></i>
                          </a> 
                        </td>
                      </tr>
                    @endforeach
                    
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="row">
                <div class="col-6">
                  <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                  Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg dopplr
                  jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                </p>
                </div>
                <div class="col-6">
                  
                </div>
              </div>

              <div class="row no-print">
                <div class="col-12">
                  <a href="{{ route('frontEnd.home') }}" class="btn btn-primary float-right"><i class="fas fa-back"></i> Back</a>
                </div>
              </div>
            </div>
     </div>
   </div>
 </div>
@endsection