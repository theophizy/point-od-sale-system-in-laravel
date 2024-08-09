<h3><strong><hr></strong></h3>
                     @if(Session::has('error_message'))
              <div class="btn btn-danger btn-block" role="alert" id="msgdiv">
              <strong>Error:</strong> {{Session::get('error_message')}}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
               </button>
                    </div>
                    @endif 
                    @if(Session::has('success_message'))
              <div class="btn btn-success btn-block" role="alert" id="msgdiv">
              <strong>Seccess:</strong> {{Session::get('success_message')}}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
               </button>
                    </div>
                    @endif 
                    @if($errors->any())
                    <div class="btn btn-danger btn-block" role="alert" id="msgdiv">
                    @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        
    </div>
@endif       