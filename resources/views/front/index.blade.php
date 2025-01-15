@extends('layouts.front')
@section('content')
    <div class="container mt-5 mx-auto">
        <div class="row mb-5">
            <div class="col-12">
                <div class="text-center">
                    <img src="{{ Setting::getFullPath('logo') }}" alt="Herkobi Panel" class="img-fluid mw-200 mb-3">
                </div>
            </div>
        </div>
        <div class="row g-2 justify-content-center mb-5">
            @foreach ($plans as $plan)
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="card-title">{{ $plan->name }}</h3>
                            @if ($plan->description)
                                <p class="card-text">{{ $plan->description }}</p>
                            @endif
                            <h4>{{ $plan->formatted_price }}</h4>
                            @if ($plan->features->count() > 0)
                                <ul class="list-unstyled mt-4">
                                    @foreach ($plan->features as $feature)
                                        <li class="mb-2">
                                            <i class="bi bi-check-circle text-success"></i>
                                            {{ $feature->name }}<br>
                                            @if ($feature->pivot->charges)
                                                ({{ (int)$feature->pivot->charges }} Adet)
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <div class="mt-4">
                                <a href="{{ route('register', ['plan' => $plan->id]) }}" class="btn btn-primary btn-sm">
                                    Hemen Başla
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-12">
                <div class="text-center">
                    <p class="mb-0">Herkobi Dijital Çözümler<br>Yazılım San. ve Tic. A.Ş.</p>
                    <p>&copy; <?php echo date('Y'); ?></p>
                </div>
            </div>
        </div>
    </div>
@endsection
