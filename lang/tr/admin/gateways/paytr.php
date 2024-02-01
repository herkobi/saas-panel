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
    'form.logo' => 'Logo',
    'logo.text' => 'Boş bırakırsanız ödeme ekranında logonuz görülmez',
    'form.merchant_id' => 'Mağaza Kodu',
    'form.merchant_key_and_salt' => 'Güvenlik Kodu ve Şifresi',
    'form.merchant_key' => 'Güvenlik Kodu',
    'form.merchant_salt' => 'Güvenlik Şifresi',
    'form.merchant_ok_url' => 'Başarılı Dönüş Adresi',
    'form.merchant_fail_url' => 'Hatalı Dönüş Adresi',

    'form.update' => 'Bilgileri Güncelle',

    'update.success' => 'Güncelleme Başarılı',

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
     * Logo Messages
     */
    'logo.images' => 'Lütfen resim seçiniz',
    'logo.max' => 'En fazla 1 MB\'lık resim yükleyiniz',
    'logo.mimes' => 'Lütfen jpg, jpeg ya da png türünde resim yükleyiniz',

    /**
     * Account ID Messages
     */
    'merchant_id.required' => 'Lütfen mağaza kodunu giriniz',

    /**
     * Account Key Messages
     */
    'merchant_key.required' => 'Lütfen mağaza anahtarını giriniz',

    /**
     * Security Code Messages
     */
    'merchant_salt.required' => 'Lütfen mağaza şifresini giriniz',

    /**
     * Success Return URL Messages
     */
    'merchant_ok_url.required' => 'Lütfen başarılı dönüş adresini giriniz',
    'merchant_ok_url.url' => 'Lütfen geçerli bir URL giriniz',

    /**
     * Error Return URL Messages
     */
    'merchant_fail_url.required' => 'Lütfen hatalı dönüş adresini giriniz',
    'merchant_fail_url.url' => 'Lütfen geçerli bir URL giriniz',

];
