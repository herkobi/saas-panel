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
                    <h3 class="form-title border-bottom mb-3 pb-3">{{ $agreementVersion->version }} Versiyonunu Düzenle</h3>
                    <form
                        action="{{ route('panel.settings.agreement.version.update', [$agreement->slug, $agreementVersion->id]) }}"
                        method="POST">
                        @csrf
                        <div class="row mb-3">
                            <label for="version" class="col-form-label col-lg-12 fw-medium">Versiyon Adı/Numarası</label>
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-end-0 bg-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-fonts" viewBox="0 0 16 16">
                                            <path
                                                d="M12.258 3h-8.51l-.083 2.46h.479c.26-1.544.758-1.783 2.693-1.845l.424-.013v7.827c0 .663-.144.82-1.3.923v.52h4.082v-.52c-1.162-.103-1.306-.26-1.306-.923V3.602l.431.013c1.934.062 2.434.301 2.693 1.846h.479z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="version" id="version"
                                        class="form-control rounded-0 border-start-0"
                                        placeholder="Versiyon Adını/Numarasını Giriniz"
                                        value="{{ old('version', $agreementVersion->version) }}" required>
                                </div>
                                <span class="form-hint small">Kullandığınız versiyon yapısına uygun olarak yeni versiyon
                                    adını/numarasını giriniz.</span>
                                @error('version')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="version" class="col-form-label col-lg-12 fw-medium">Sözleşme İçeriği</label>
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <div class="input-group-text align-items-start pt-2 rounded-0 border-end-0 bg-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-body-text" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M0 .5A.5.5 0 0 1 .5 0h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 0 .5m0 2A.5.5 0 0 1 .5 2h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m9 0a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-9 2A.5.5 0 0 1 .5 4h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m5 0a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m7 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m-12 2A.5.5 0 0 1 .5 6h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m8 0a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-8 2A.5.5 0 0 1 .5 8h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m7 0a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-7 2a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 0 1h-8a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5" />
                                        </svg>
                                    </div>
                                    <textarea name="content" id="content" class="form-control rounded-0 border-start-0" rows="12"
                                        placeholder="Sözleşme İçeriğini Giriniz" required>{{ old('content', $agreementVersion->content) }}</textarea>
                                </div>
                                <span class="form-hint small">Sözleşme içeriğini giriniz</span>
                                @error('content')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-5">
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
                                @if ($agreementVersion->status === AgreementVersionStatus::DRAFT)
                                    <div>
                                        <button type="button" class="btn btn-primary border-0 me-3" data-bs-toggle="modal"
                                            data-bs-target="#publishModal">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                                <path
                                                    d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                                <path
                                                    d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
                                            </svg>
                                            YAYINLA
                                        </button>
                                        <button type="button" class="btn btn-danger border-0" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal">
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
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if ($agreementVersion->status === AgreementVersionStatus::DRAFT)
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form
                        action="{{ route('panel.settings.agreement.version.destroy', [$agreement->slug, $agreementVersion->id]) }}"
                        method="post">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="deleteModalLabel">Dikkat!</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <strong>{{ $agreement->title }}</strong> sözleşmesine ait
                            <strong>{{ $agreementVersion->version }}</strong>
                            versiyonunu silmek üzeresiniz. Bu işlem geri alınamaz. Emin misiniz?
                        </div>
                        <div class="modal-footer">
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <button type="button" class="btn bg-white border-0 text-dark"
                                    data-bs-dismiss="modal">İptal
                                    Et</button>
                                <button type="submit" class="btn">Evet, Versiyonu Sil</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="publishModal" tabindex="-1" aria-labelledby="publishModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form
                        action="{{ route('panel.settings.agreement.version.publish', [$agreement->slug, $agreementVersion->id]) }}"
                        method="post">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="publishModalLabel">Versiyon Yayınlama</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <strong>{{ $agreement->title }}</strong> sözleşmesine ait
                                <strong>{{ $agreementVersion->version }}</strong>
                                versiyonunu yayınlamadan önce aşağıdaki seçenekleri kontrol edin
                            </div>
                            @if ($agreement->user_type === UserType::USER)
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="block_access"
                                            name="block_access" value="1">
                                        <label class="form-check-label" for="block_access">Kabul edilmeden sisteme erişimi
                                            engelle</label>
                                        <small class="form-text text-muted d-block">Bu seçenek işaretlenirse, sözleşme
                                            kabul zorunluluğu ve bildirim gönderme otomatik olarak aktif edilir.</small>
                                    </div>
                                </div>
                            @endif
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="require_acceptance"
                                        name="require_acceptance" value="1">
                                    <label class="form-check-label" for="require_acceptance">Kullanıcıların kabul etmesi
                                        zorunlu olsun</label>
                                    <small class="form-text text-muted d-block">Bu seçenek işaretlenirse, bildirim gönderme
                                        otomatik olarak aktif edilir.</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="send_notification"
                                        name="send_notification" value="1">
                                    <label class="form-check-label" for="send_notification">Kullanıcılara bildirim
                                        gönder</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn bg-white border-0 text-dark" data-bs-dismiss="modal">İptal
                                Et</button>
                            <button type="submit" class="btn btn-success">Yayınla</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const blockAccess = document.getElementById('block_access');
            const requireAcceptance = document.getElementById('require_acceptance');
            const sendNotification = document.getElementById('send_notification');

            // Block Access var ise
            if (blockAccess) {
                blockAccess.addEventListener('change', function() {
                    if (this.checked) {
                        requireAcceptance.checked = true;
                        sendNotification.checked = true;

                        requireAcceptance.disabled = true;
                        sendNotification.disabled = true;
                    } else {
                        requireAcceptance.disabled = false;
                        sendNotification.disabled = false;
                    }
                });
            }

            // Require Acceptance her durumda çalışmalı
            if (requireAcceptance) {
                requireAcceptance.addEventListener('change', function() {
                    if (this.checked) {
                        sendNotification.checked = true;
                        sendNotification.disabled = true;
                    } else {
                        // Block Access yoksa veya check edilmemişse
                        if (!blockAccess || !blockAccess.checked) {
                            sendNotification.disabled = false;
                        }
                    }
                });
            }
        });
    </script>
@endsection
