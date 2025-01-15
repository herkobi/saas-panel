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
                    @include('admin.settings.payments.include.sidebar')
                </div>
                <div class="col-lg-9">
                    <h3 class="form-title border-bottom mb-3 pb-3">Banka Havalesi / EFT Bilgisini Düzenle</h3>
                    <form action="{{ route('panel.settings.payments.bac.update', $bacs->id) }}" method="POST">
                        @csrf
                        <div class="row align-items-center mb-3">
                            <label class="col-lg-2 col-md-3 col-form-label">Durum</label>
                            <div class="col-lg-10 col-md-9">
                                <div>
                                    @foreach (Status::cases() as $type)
                                        <label class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status"
                                                value="{{ $type->value }}"
                                                {{ $type->value == $bacs->status->value ? 'checked' : '' }}>
                                            <span class="form-check-label">{{ Status::getTitle($type->value) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('status')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-form-label col-lg-2 col-md-3">Para Birimi</label>
                            <div class="col-lg-10 col-md-9">
                                <select class="form-select" name="currency_id">
                                    <option>Lütfen Seçiniz</option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}"
                                            {{ $bacs->currency_id == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="form-hint small">Para birimini seçiniz.</span>
                                @error('currency_id')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="bank_name" class="col-form-label col-lg-2 col-md-3">Banka Adı</label>
                            <div class="col-lg-10 col-md-9">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-end-0 bg-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-safe2" viewBox="0 0 16 16">
                                            <path
                                                d="M1 2.5A1.5 1.5 0 0 1 2.5 1h12A1.5 1.5 0 0 1 16 2.5v12a1.5 1.5 0 0 1-1.5 1.5h-12A1.5 1.5 0 0 1 1 14.5V14H.5a.5.5 0 0 1 0-1H1V9H.5a.5.5 0 0 1 0-1H1V4H.5a.5.5 0 0 1 0-1H1zM2.5 2a.5.5 0 0 0-.5.5v12a.5.5 0 0 0 .5.5h12a.5.5 0 0 0 .5-.5v-12a.5.5 0 0 0-.5-.5z" />
                                            <path
                                                d="M5.035 8h1.528q.072-.277.214-.516l-1.08-1.08A3.5 3.5 0 0 0 5.035 8m1.369-2.303 1.08 1.08q.24-.142.516-.214V5.035a3.5 3.5 0 0 0-1.596.662M9 5.035v1.528q.277.072.516.214l1.08-1.08A3.5 3.5 0 0 0 9 5.035m2.303 1.369-1.08 1.08q.142.24.214.516h1.528a3.5 3.5 0 0 0-.662-1.596M11.965 9h-1.528q-.072.277-.214.516l1.08 1.08A3.5 3.5 0 0 0 11.965 9m-1.369 2.303-1.08-1.08q-.24.142-.516.214v1.528a3.5 3.5 0 0 0 1.596-.662M8 11.965v-1.528a2 2 0 0 1-.516-.214l-1.08 1.08A3.5 3.5 0 0 0 8 11.965m-2.303-1.369 1.08-1.08A2 2 0 0 1 6.563 9H5.035c.085.593.319 1.138.662 1.596M4 8.5a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0m4.5-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
                                        </svg>
                                    </div>
                                    <input type="text" name="bank_name" id="bank_name"
                                        class="form-control rounded-0 border-start-0" placeholder="Banka Adını Giriniz"
                                        value="{{ old('bank_name', $bacs->bank_name) }}" required>
                                </div>
                                <span class="form-hint small">Banka adını giriniz</span>
                                @error('bank_name')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-form-label col-lg-2 col-md-3">Logo</label>
                            <div class="col-lg-10 col-md-9">
                                <input type="file" name="logo" class="form-control mb-2" placeholder="Banka Logosu"
                                    value="{{ old('logo') }}">
                                @if (!empty($bacs->logo))
                                    <div class="d-block">
                                        <img id="preview_logo" class="img-fluid" src="{{ $bacs->logo }}"
                                            alt="{{ $bacs->bank_name }}">
                                    </div>
                                @endif
                                <span class="form-hint small">Banka logosunu yükleyiniz. Zorunlu değildir.</span>
                                @error('logo')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="account_holder" class="col-form-label col-lg-2 col-md-3">Hesap Sahibi</label>
                            <div class="col-lg-10 col-md-9">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-end-0 bg-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-person-badge" viewBox="0 0 16 16">
                                            <path
                                                d="M6.5 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                            <path
                                                d="M4.5 0A2.5 2.5 0 0 0 2 2.5V14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2.5A2.5 2.5 0 0 0 11.5 0zM3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5v10.795a4.2 4.2 0 0 0-.776-.492C11.392 12.387 10.063 12 8 12s-3.392.387-4.224.803a4.2 4.2 0 0 0-.776.492z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="account_holder" id="account_holder"
                                        class="form-control rounded-0 border-start-0" placeholder="Hesap Sahibini Giriniz"
                                        value="{{ old('account_holder', $bacs->account_holder) }}" required>
                                </div>
                                <span class="form-hint small">Hesap sahibinin adını giriniz</span>
                                @error('account_holder')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="account_number" class="col-form-label col-lg-2 col-md-3">Hesap Numarası</label>
                            <div class="col-lg-10 col-md-9">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-end-0 bg-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-123" viewBox="0 0 16 16">
                                            <path
                                                d="M2.873 11.297V4.142H1.699L0 5.379v1.137l1.64-1.18h.06v5.961zm3.213-5.09v-.063c0-.618.44-1.169 1.196-1.169.676 0 1.174.44 1.174 1.106 0 .624-.42 1.101-.807 1.526L4.99 10.553v.744h4.78v-.99H6.643v-.069L8.41 8.252c.65-.724 1.237-1.332 1.237-2.27C9.646 4.849 8.723 4 7.308 4c-1.573 0-2.36 1.064-2.36 2.15v.057zm6.559 1.883h.786c.823 0 1.374.481 1.379 1.179.01.707-.55 1.216-1.421 1.21-.77-.005-1.326-.419-1.379-.953h-1.095c.042 1.053.938 1.918 2.464 1.918 1.478 0 2.642-.839 2.62-2.144-.02-1.143-.922-1.651-1.551-1.714v-.063c.535-.09 1.347-.66 1.326-1.678-.026-1.053-.933-1.855-2.359-1.845-1.5.005-2.317.88-2.348 1.898h1.116c.032-.498.498-.944 1.206-.944.703 0 1.206.435 1.206 1.07.005.64-.504 1.106-1.2 1.106h-.75z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="account_number" id="account_number"
                                        class="form-control rounded-0 border-start-0"
                                        placeholder="Hesap Numarasını Giriniz"
                                        value="{{ old('account_number', $bacs->account_number) }}">
                                </div>
                                <span class="form-hint small">Hesap numarasını giriniz. Şube kodu ile birlikte
                                    giriniz. Hesap numarası ya da IBAN numarasından birini girmeniz gerekmektedir.</span>
                                @error('account_number')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="iban" class="col-form-label col-lg-2 col-md-3">IBAN Numarası</label>
                            <div class="col-lg-10 col-md-9">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-end-0 bg-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="iban" id="iban"
                                        class="form-control rounded-0 border-start-0"
                                        placeholder="IBAN Numarasını Giriniz" value="{{ old('iban', $bacs->iban) }}">
                                </div>
                                <span class="form-hint small">IBAN numarasını giriniz. Hesap numarası ya da IBAN
                                    numarasından birini girmeniz gerekmektedir.</span>
                                @error('iban')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-5">
                            <label for="swift" class="col-form-label col-lg-2 col-md-3">Swift Kodu</label>
                            <div class="col-lg-10 col-md-9">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-end-0 bg-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-globe-europe-africa" viewBox="0 0 16 16">
                                            <path
                                                d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0M3.668 2.501l-.288.646a.847.847 0 0 0 1.479.815l.245-.368a.81.81 0 0 1 1.034-.275.81.81 0 0 0 .724 0l.261-.13a1 1 0 0 1 .775-.05l.984.34q.118.04.243.054c.784.093.855.377.694.801-.155.41-.616.617-1.035.487l-.01-.003C8.274 4.663 7.748 4.5 6 4.5 4.8 4.5 3.5 5.62 3.5 7c0 1.96.826 2.166 1.696 2.382.46.115.935.233 1.304.618.449.467.393 1.181.339 1.877C6.755 12.96 6.674 14 8.5 14c1.75 0 3-3.5 3-4.5 0-.262.208-.468.444-.7.396-.392.87-.86.556-1.8-.097-.291-.396-.568-.641-.756-.174-.133-.207-.396-.052-.551a.33.33 0 0 1 .42-.042l1.085.724c.11.072.255.058.348-.035.15-.15.415-.083.489.117.16.43.445 1.05.849 1.357L15 8A7 7 0 1 1 3.668 2.501" />
                                        </svg>
                                    </div>
                                    <input type="text" name="swift" id="swift"
                                        class="form-control rounded-0 border-start-0" placeholder="Swift Kodunu Giriniz"
                                        value="{{ old('swift', $bacs->swift) }}">
                                </div>
                                <span class="form-hint small">Yurtdışından ödeme alacaksanız SWIFT koduna ihtiyacınız
                                    vardır. Yurtdışı müşterileriniz için SWIFT kodunu giriniz.</span>
                                @error('swift')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-lg-10 col-mg-9 offset-lg-2 offset-md-3">
                                <div class="d-flex align-items-center justify-content-between w-100">
                                    <button type="submit" class="btn rounded-1 px-5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-floppy" viewBox="0 0 20 20">
                                            <path d="M11 2H9v3h2z" />
                                            <path
                                                d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z" />
                                        </svg>
                                        GÜNCELLE
                                    </button>
                                    <button type="button" class="btn bg-white border-0 text-danger"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                            <path
                                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                            <path
                                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                        </svg>
                                        SİL
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('panel.settings.payments.bac.delete', $bacs->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteModalLabel">Dikkat!</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <strong>{{ $bacs->bank_name }}</strong> bankasına ait hesap bilgisini silmek üzeresiniz. Bu işlem
                        geri alınamaz. Emin misiniz?
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <button type="button" class="btn bg-white border-0 text-dark" data-bs-dismiss="modal">İptal
                                Et</button>
                            <button type="submit" class="btn">Evet, Hesap Bilgisini Sil</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
