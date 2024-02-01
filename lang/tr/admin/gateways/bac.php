<?php


return [

    'page.title' => 'EFT/Havale İle Ödeme',
    'page.text' => 'Ödemelerinizi eft/havale ile alın',
    'edit.page.title' => 'Bilgileri Düzenle',
    'create.page.title' => 'Yeni Hesap/Banka Bilgisi Ekle',

    'add.button' => 'Yeni Ekle',
    'table.status' => 'Durum',
    'table.bac' => 'Başlık',
    'table.bank' => 'Hesap Bilgileri',
    'table.edit' => 'Düzenle',

    'form.status' => 'Durum',
    'form.title' => 'Hesap Adı',
    'form.desc' => 'Açıklama',
    'form.currency_id' => 'Para Birimi',
    'form.account_name' => 'Hesap Sahibi',
    'form.account_bank' => 'Banka Adı',
    'form.account_branch_and_number' => 'Şube Kodu ve Hesap Numarası',
    'form.account_branch' => 'Şube Kodu',
    'form.account_number' => 'Hesap Numarası',
    'form.account_iban' => 'IBAN Numarası',
    'form.account_swift' => 'SWIFT Kodu',

    'form.submit' => 'Hesap Bilgisini Ekle',
    'form.update' => 'Bilgileri Güncelle',
    'form.delete' => 'Hesap Bilgisini Sil',

    'confirm.title' => 'Hesap Bilgisini Sil',
    'confirm.text' => 'Bu işlem geri alınamaz. Lütfen silme işleminden önce gerekli kontrolleri yaptığınızdan emin olunuz.',
    'confirm.cancel' => 'İptal Et',
    'confirm.submit' => 'Evet, Sil',

    'store.success' => 'Hesap Bilgisi başarılı bir şekilde eklendi',
    'update.success' => 'Güncelleme Başarılı',
    'destroy.success' => 'Hesap bilgisi başarılı bir şekilde silindi',
    'destroy.error' => 'Kayıt silinemedi. Lütfen ilgili kayıtları kontrol ediniz',

    /**
     * Status Messages
     */
    'status.required' => 'Durum seçiniz',
    'status.integer' => 'Durum rakam olmalıdır',

    /**
     * Title Messages
     */
    'title.required' => 'Başlık giriniz',
    'title.string' => 'Lütfen geçerli içerik giriniz',
    'title.max' => 'Daha kısa içerik giriniz',

    /**
     * Currency Messages
     */
    'currency_id.exists' => 'Geçerli bir ödeme türü seçiniz',
    'currency_id.numeric' => 'Ödeme türü değeri rakam olmalıdır',

    /**
     * Account Name Messages
     */
    'account_name.required' => 'Lütfen hesap sahibini giriniz',
    'account_name.string' => 'Lütfen geçerli bir içerik giriniz',
    'account_name.max' => 'Lütfen daha kısa içerik giriniz',

    /**
     * Account Bank Messages
     */
    'account_bank.required' => 'Lütfen banka adını giriniz',
    'account_bank.string' => 'Lütfen geçerli bir içerik giriniz',
    'account_bank.max' => 'Lütfen daha kısa içerik giriniz',

    /**
     * Account Branch Messages
     */
    'account_branch.numeric' => 'Lütfen şube kodunu giriniz',
    'account_branch.required_with' => 'Lütfen hesap numarasını da giriniz',

    /**
     * Account Number Messages
     */
    'account_number.numeric' => 'Lütfen hesap numarasını giriniz',
    'account_number.required_with' => 'Lütfen şube kodunu da giriniz',

    /**
     * Account IBAN Messages
     */
    'account_iban.required_without_all' => 'Lütfen IBAN bilgisini giriniz',

];
