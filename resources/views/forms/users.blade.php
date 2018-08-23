@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">

                    @if (isset($user))
                        {{ Form::open(['url' => '/user/' . $user->id, 'method' => 'put', 'class'=>'form-horizontal']) }}
                    @else
                        {{ Form::open(['url'=>'/user', 'method' => 'post', 'class'=>'form-horizontal', 'id' => 'samples_form']) }}
                    @endif

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Partners</label>

                            <div class="col-md-6">
                                <select class="btn filters form-control" id="filter_partner" name="partner_id">
                                    <option selected='true'>Select Partner</option>

                                    @foreach($partners as $partner)
                                        <option value="{{ $partner->id }}"
                                            @if(isset($user) && $user->partner_id == $partner->id)
                                                selected
                                            @endif
                                            > {{ $partner->name }} </option>
                                    @endforeach
                                </select>   
                            </div>    
                        </div> 

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">User Type</label>

                            <div class="col-md-6">
                                <select class="btn filters form-control" id="filter_partner" name="user_type_id">
                                    <option selected='true'>Select User Type</option>
                                    <option value="1"> Admin </option>
                                    <option value="2"> Partner </option>
                                </select>   
                            </div>    
                        </div> 

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $user->name ?? '' }}" required>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $user->email ?? '' }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{--<div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" required>
                            </div>
                        </div>--}}

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ secure_asset('js/validate/jquery.validate.min.js') }}"></script>
<script type="text/javascript">
    $(function() {
        $(".filters").select2();

        $(".form-horizontal").validate({
            errorPlacement: function (error, element)
            {
                element.before(error);
            }
            {{ $val_rules ?? '' }}
        });

    });
</script>
@endsection
