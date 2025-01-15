@extends('layouts.app')
@section('content')
    @include('user.include.header', [
        'title' => 'Başlangıç',
    ])
    <div class="page-content mt-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Onaylanması Gereken Sözleşmeler</h4>
                        </div>
                        <div class="card-body">
                            @if ($agreements->isEmpty())
                                <div class="alert alert-success mb-0">
                                    Onaylamanız gereken sözleşme bulunmamaktadır.
                                </div>
                            @else
                                <div class="alert alert-info">
                                    Aşağıdaki sözleşmeleri inceleyip onaylamanız gerekmektedir.
                                </div>

                                @foreach ($agreements as $agreement)
                                    @php $latestVersion = $agreement->versions->first() @endphp
                                    <div class="border rounded p-3 mb-3">
                                        <h5>{{ $agreement->title }}</h5>
                                        @if ($latestVersion->require_acceptance)
                                            <div class="alert alert-warning">
                                                Bu sözleşme versiyonu için onay zorunluluğu bulunmaktadır.
                                            </div>
                                        @endif

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                                data-bs-target="#modal-{{ $agreement->id }}">
                                                Sözleşmeyi Görüntüle
                                            </button>

                                            <form action="{{ route('app.agreement.accept') }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <input type="hidden" name="agreement_id" value="{{ $agreement->id }}">
                                                <input type="hidden" name="version_id" value="{{ $latestVersion->id }}">
                                                <button type="submit" class="btn btn-primary">Kabul Ediyorum</button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Modal -->
                                    <div class="modal fade" id="modal-{{ $agreement->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ $agreement->title }}</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {!! nl2br(e($latestVersion->content)) !!}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Kapat</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
