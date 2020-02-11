@foreach($messages as $message)
	@include('frontend.partials.message', ['message' => $message])
@endforeach