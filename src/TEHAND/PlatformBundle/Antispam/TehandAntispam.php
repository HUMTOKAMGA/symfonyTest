<?php

namespace TEHAND\PlatformBundle\Antispam;

class TehandAntispam {

    /**
     *
     * @var any 
     */
    private $mailer;
    private $locale;
    private $minLength;

    /**
     * 
     * @param \Swift_Mailer $mailer
     * @param type $locale
     * @param type $minLength
     */
    public function __construct(\Swift_Mailer $mailer, $locale, $minLength) {
        $this->mailer = $mailer;
        $this->locale = $locale;
        $this->minLength = (int) $minLength;
    }

    /**
     * 
     * @param string $text
     * @return bool
     */
    public function isSpam($text) {
        return strlen($text) < $this->minLength;
    }

}
