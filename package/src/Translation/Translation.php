<?php
namespace Concrete\Package\CommunityTranslation\Src\Translation;

use Concrete\Package\CommunityTranslation\Src\Locale\Locale;
use Concrete\Package\CommunityTranslation\Src\Translatable\Translatable;
use DateTime;

/**
 * Represents an translatable string.
 *
 * @Entity
 * @Table(
 *     name="Translations",
 *     options={"comment": "Translated strings"}
 * )
 */
class Translation
{
    // Properties

    /**
     * Translation ID.
     *
     * @Id @Column(type="integer", options={"unsigned": true, "comment": "Translation ID"})
     * @GeneratedValue(strategy="AUTO")
     *
     * @var int|null
     */
    protected $tID;

    /**
     * Associated Locale.
     *
     * @ManyToOne(targetEntity="Concrete\Package\CommunityTranslation\Src\Locale\Locale")
     * @JoinColumn(name="tLocale", referencedColumnName="lID", nullable=false, onDelete="CASCADE")
     *
     * @var string
     */
    protected $tLocale;

    /**
     * Associated Translatable.
     *
     * @ManyToOne(targetEntity="Concrete\Package\CommunityTranslation\Src\Translatable\Translatable")
     * @JoinColumn(name="tTranslatable", referencedColumnName="tID", nullable=false, onDelete="CASCADE")
     *
     * @var int
     */
    protected $tTranslatable;

    /**
     * Record creation date/time.
     *
     * @Column(type="datetime", nullable=false, options={"comment": "Record creation date/time"})
     *
     * @var DateTime
     */
    protected $tCreatedOn;

    /**
     * ID of the user that submitted this translation.
     *
     * @Column(type="integer", nullable=false, options={"unsigned": true, "comment": "ID of the user that submitted this translation"})
     *
     * @var int
     */
    protected $tCreatedBy;

    /**
     * Status of this translation (0: neither current not approved, 1: current but not approved, 2: current and approved).
     *
     * @Column(type="smallint", nullable=false, options={"unsigned": true, "comment": "Status of this translation (0: neither current not approved, 1: current but not approved, 2: current and approved)"})
     *
     * @var int
     */
    protected $tStatus;

    /**
     * Translation (singular / plural 0).
     *
     * @Column(type="text", nullable=false, options={"comment": "Translation (singular / plural 0)"})
     *
     * @var string
     */
    protected $tText0;

    /**
     * Translation (plural 1).
     *
     * @Column(type="text", nullable=false, options={"comment": "Translation (plural 1)"})
     *
     * @var string
     */
    protected $tText1;

    /**
     * Translation (plural 2).
     *
     * @Column(type="text", nullable=false, options={"comment": "Translation (plural 2)"})
     *
     * @var string
     */
    protected $tText2;

    /**
     * Translation (plural 3).
     *
     * @Column(type="text", nullable=false, options={"comment": "Translation (plural 3)"})
     *
     * @var string
     */
    protected $tText3;

    /**
     * Translation (plural 4).
     *
     * @Column(type="text", nullable=false, options={"comment": "Translation (plural 4)"})
     *
     * @var string
     */
    protected $tText4;

    /**
     * Translation (plural 5).
     *
     * @Column(type="text", nullable=false, options={"comment": "Translation (plural 5)"})
     *
     * @var string
     */
    protected $tText5;

    // Constructors

    /**
     * @return self
     */
    public static function createNew()
    {
        $new = new self();
        $new->tCreatedOn = new DateTime();
        $new->tText1 = '';
        $new->tText2 = '';
        $new->tText3 = '';
        $new->tText4 = '';
        $new->tText5 = '';

        return $new;
    }

    // Gettext and setters

    /**
     * Get the Translation ID.
     *
     * @return int
     */
    public function getID()
    {
        return $this->tID;
    }

    /**
     * Get the associated Locale.
     *
     * @return Locale
     */
    public function gettLocale()
    {
        return $this->tLocale;
    }

    /**
     * Set the associated Locale.
     *
     * @param Locale $value
     */
    public function setLocale(Locale $value)
    {
        $this->tLocale = $value;
    }

    /**
     * Get the associated Translatable.
     *
     * @return Translatable
     */
    public function getTranslatable()
    {
        return $this->tTranslatable;
    }

    /**
     * Set the associated Translatable.
     *
     * @param Translatable $value
     */
    public function setTranslatable(Translatable $value)
    {
        $this->tTranslatable = $value;
    }

    /**
     * Get the record creation date/time.
     *
     * @return \DateTime 
     */
    public function getCreatedOn()
    {
        return $this->tCreatedOn;
    }

    /**
     * Get the ID of the user that submitted this translation.
     *
     * @return int
     */
    public function getCreatedBy()
    {
        return $this->tCreatedBy;
    }

    /**
     * Set the ID of the user that submitted this translation.
     *
     * @param int $value
     */
    public function setCreatedBy($value)
    {
        $this->tCreatedBy = (int) $value;
    }

    /**
     * Get the status of this translation (0: neither current not approved, 1: current but not approved, 2: current and approved).
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->tStatus;
    }

    /**
     * Set the status of this translation (0: neither current not approved, 1: current but not approved, 2: current and approved).
     *
     * @param int $value
     */
    public function setStatus($value)
    {
        $this->tStatus = (int) $value;
    }

    /**
     * Get the translation (singular / plural 0).
     *
     * @return string 
     */
    public function getText0()
    {
        return $this->tText0;
    }

    /**
     * Set the translation (singular / plural 0).
     *
     * @param string $value
     */
    public function setText0($value)
    {
        $this->tText0 = (string) $value;
    }

    /**
     * Get the translation (plural 1).
     *
     * @return string
     */
    public function getText1()
    {
        return $this->tText1;
    }

    /**
     * Set the translation (plural 1).
     *
     * @param string $value
     */
    public function setText1($value)
    {
        $this->tText1 = (string) $value;
    }

    /**
     * Get the translation (plural 2).
     *
     * @return string
     */
    public function getText2()
    {
        return $this->tText2;
    }

    /**
     * Set the translation (plural 2).
     *
     * @param string $value
     */
    public function setText2($value)
    {
        $this->tText2 = (string) $value;
    }

    /**
     * Get the translation (plural 3).
     *
     * @return string
     */
    public function getText3()
    {
        return $this->tText3;
    }

    /**
     * Set the translation (plural 3).
     *
     * @param string $value
     */
    public function setText3($value)
    {
        $this->tText3 = (string) $value;
    }

    /**
     * Get the translation (plural 4).
     *
     * @return string
     */
    public function getText4()
    {
        return $this->tText4;
    }

    /**
     * Set the translation (plural 4).
     *
     * @param string $value
     */
    public function setText4($value)
    {
        $this->tText4 = (string) $value;
    }

    /**
     * Get the translation (plural 5).
     *
     * @return string
     */
    public function getText5()
    {
        return $this->tText5;
    }

    /**
     * Set the translation (plural 5).
     *
     * @param string $value
     */
    public function setText5($value)
    {
        $this->tText5 = (string) $value;
    }
}