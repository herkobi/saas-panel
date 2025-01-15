@extends('layouts.panel')
@section('content')
    @include('admin.include.header', [
        'title' => 'Ayarlar',
    ])
    @include('admin.settings.include.navigation')
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('admin.settings.agreements.include.sidebar')
                </div>
                <div class="col-lg-9">
                    <h3 class="form-title border-bottom mb-3 pb-3">Yeni Sözleşme</h3>
                    <form action="{{ route('panel.settings.agreement.user.store') }}" method="POST">
                        @csrf
                        <div class="row align-items-center mb-3">
                            <label class="col-lg-3 col-md-4 col-form-label">Durum</label>
                            <div class="col-lg-9 col-md-8">
                                <div>
                                    @foreach (Status::cases() as $type)
                                        <label class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status"
                                                value="{{ $type->value }}" {{ 1 == $type->value ? 'checked' : '' }}>
                                            <span class="form-check-label">{{ Status::getTitle($type->value) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center mb-3">
                            <label class="col-lg-3 col-md-4 col-form-label">Üyelik Formunda Göster</label>
                            <div class="col-lg-9 col-md-8">
                                <div>
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="show_on_register"
                                            value="1" {{ old('show_on_register', true) === true ? 'checked' : '' }}>
                                        <span class="form-check-label">EVET</span>
                                    </label>
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="show_on_register"
                                            value="0" {{ old('show_on_register', true) === false ? 'checked' : '' }}>
                                        <span class="form-check-label">HAYIR</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center mb-3">
                            <label class="col-lg-3 col-md-4 col-form-label">Ödeme Formunda Göster</label>
                            <div class="col-lg-9 col-md-8">
                                <div>
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="show_on_payment" value="1"
                                            {{ old('show_on_payment', true) === true ? 'checked' : '' }}>
                                        <span class="form-check-label">EVET</span>
                                    </label>
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="show_on_payment" value="0"
                                            {{ old('show_on_payment', true) === false ? 'checked' : '' }}>
                                        <span class="form-check-label">HAYIR</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="title" class="col-form-label col-lg-3 col-md-4">Sözleşme Adı</label>
                            <div class="col-lg-9 col-md-8">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-end-0 bg-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-fonts" viewBox="0 0 16 16">
                                            <path
                                                d="M12.258 3h-8.51l-.083 2.46h.479c.26-1.544.758-1.783 2.693-1.845l.424-.013v7.827c0 .663-.144.82-1.3.923v.52h4.082v-.52c-1.162-.103-1.306-.26-1.306-.923V3.602l.431.013c1.934.062 2.434.301 2.693 1.846h.479z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="title" id="title"
                                        class="form-control rounded-0 border-start-0" placeholder="Sözleşme Adını Giriniz"
                                        value="{{ old('title') }}" required>
                                </div>
                                <span class="form-hint small">Sözleşme adını giriniz</span>
                                @error('title')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="description" class="col-form-label col-lg-3 col-md-4">Sözleşme Açıklaması</label>
                            <div class="col-lg-9 col-md-8">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-end-0 bg-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-blockquote-left" viewBox="0 0 16 16">
                                            <path
                                                d="M2.5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm5 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm-5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm.79-5.373q.168-.117.444-.275L3.524 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562Q2 7.587 2 7.969q0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282q.036-.305.123-.498a1.4 1.4 0 0 1 .252-.37 2 2 0 0 1 .346-.298zm2.167 0q.17-.117.445-.275L5.692 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562q-.165.31-.164.692 0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282a1.8 1.8 0 0 1 .118-.492q.087-.194.257-.375a2 2 0 0 1 .346-.3z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="description" id="description"
                                        class="form-control rounded-0 border-start-0"
                                        placeholder="Sözleşme Açıklaması Giriniz" value="{{ old('description') }}">
                                </div>
                                <span class="form-hint small">Sözleşme açıklaması giriniz</span>
                                @error('description')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-lg-9 col-mg-8 offset-lg-3 offset-md-4">
                                <button type="submit" class="btn rounded-1 px-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        fill="currentColor" class="bi bi-floppy" viewBox="0 0 20 20">
                                        <path d="M11 2H9v3h2z" />
                                        <path
                                            d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z" />
                                    </svg>
                                    KAYDET
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
