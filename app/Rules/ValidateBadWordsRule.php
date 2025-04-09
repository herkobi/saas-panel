<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateBadWordsRule implements Rule
{
    /**
     * The input attribute
     *
     * @var string
     */
    private $attribute;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->attribute = $attribute;

        // Yasaklı kelimeleri yükle
        $bannedWords = config('data.badwords', []);

        // Değeri küçük harfe çevir ve Türkçe karakterleri normalize et
        $normalizedValue = $this->normalizeTurkishChars(mb_strtolower($value));

        foreach ($bannedWords as $word) {
            // Yasaklı kelimenin de slug versiyonunu kontrol etmek için normalize ediyoruz
            $normalizedWord = $this->normalizeTurkishChars(mb_strtolower($word));

            // Gelişmiş regex ile kelimeyi tam olarak tespit et
            $pattern = '/(^|\s|[-_,.])' . preg_quote($normalizedWord, '/') . '($|\s|[-_,.])/u';

            if (preg_match($pattern, $normalizedValue)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Türkçe karakterleri İngilizce karşılıklarına çevirir.
     *
     * @param string $text
     * @return string
     */
    private function normalizeTurkishChars($text)
    {
        $search = ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç'];
        $replace = ['i', 'g', 'u', 's', 'o', 'c'];

        return str_replace($search, $replace, $text);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Girdiğiniz ":attribute" uygunsuz içerik barındırıyor.', ['attribute' => $this->attribute]);
    }
}
