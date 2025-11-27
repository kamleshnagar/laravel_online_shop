@if (Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h4>
            <i class="icon fas fa-exclamation-triangle"></i>
            Error!
        </h4>
        {{ Session::get('error') }}

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <h4>
              <h4><i class="icon fa fa-check"></i>
            Success!
        </h4>
        {{ Session::get('success') }}

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
