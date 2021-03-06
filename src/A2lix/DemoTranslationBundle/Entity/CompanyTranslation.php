<?php

namespace A2lix\DemoTranslationBundle\Entity;

use A2lix\I18nDoctrineBundle\Doctrine as A2lixI18n;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CompanyTranslation implements A2lixI18n\Interfaces\OneLocaleInterface
{
    use A2lixI18n\ORM\Util\Translation;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
