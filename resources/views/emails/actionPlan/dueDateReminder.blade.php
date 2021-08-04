@extends('emails.emaillayout')

@section('content')

<p>Dear {{ $action_plan->users->first->name->name }},</p>

<p>Kindly note that the below action plan is not approved yet:</p>

<p><a href='{!!url('action-plans/'. $action_plan->id)!!}'>{{ $action_plan->title }}</a></p>

@endsection
