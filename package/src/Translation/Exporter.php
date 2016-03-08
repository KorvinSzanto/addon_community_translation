<?php
namespace Concrete\Package\CommunityTranslation\Src\Translation;

use Concrete\Core\Application\Application;
use Concrete\Package\CommunityTranslation\Src\Locale\Locale;
use Concrete\Package\CommunityTranslation\Src\Package\Package;
use Concrete\Package\CommunityTranslation\Src\UserException;

class Exporter implements \Concrete\Core\Application\ApplicationAwareInterface
{
    /**
     * The application object.
     *
     * @var Application
     */
    protected $app;

    /**
     * Set the application object.
     *
     * @param Application $application
     */
    public function setApplication(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Fill in the translations for a specific locale.
     *
     * @param \Gettext\Translations $translations
     * @param Locale $locale
     *
     * @return \Gettext\Translations
     */
    public function fromPot(\Gettext\Translations $pot, Locale $locale)
    {
        $cn = $this->app->make('community_translation/em')->getConnection();
        $po = clone $pot;
        $po->setLanguage($locale->getID());
        $numPlurals = $locale->getPluralCount();
        $searchQuery = $cn->prepare('
            select
                Translations.*
            from
                Translatables
                inner join Translations on Translatables.tID = Translations.tTranslatable
            where
                Translatables.tHash = ?
                and Translations.tLocale = '.$cn->quote($locale->getID()).'
            limit 1
        ')->getWrappedStatement();
        foreach ($po as $translationKey => $translation) {
            $plural = $translation->getPlural();
            $hash = md5(($plural === '') ? $translationKey : "$translationKey\005$plural");
            $searchQuery->execute(array($hash));
            $row = $searchQuery->fetch();
            $searchQuery->closeCursor();
            if ($row !== false) {
                $translation->setTranslation($row['tText0']);
                if ($plural !== '') {
                    switch ($numPlurals) {
                        case 6:
                            $translation->setPluralTranslation($row['tText5'], 4);
                            /* @noinspection PhpMissingBreakStatementInspection */
                        case 5:
                            $translation->setPluralTranslation($row['tText4'], 3);
                            /* @noinspection PhpMissingBreakStatementInspection */
                        case 4:
                            $translation->setPluralTranslation($row['tText3'], 2);
                            /* @noinspection PhpMissingBreakStatementInspection */
                        case 3:
                            $translation->setPluralTranslation($row['tText2'], 1);
                            /* @noinspection PhpMissingBreakStatementInspection */
                        case 2:
                            $translation->setPluralTranslation($row['tText1'], 0);
                            /* @noinspection PhpMissingBreakStatementInspection */
                            break;
                    }
                }
            }
        }

        return $po;
    }

    /**
     * Get recordset of the translations for a specific package, version and locale.
     * @param Package $package
     * @param Locale $locale
     *
     * @return \Concrete\Core\Database\Driver\PDOStatement
     */
    public function getPackageSelectQuery(Package $package, Locale $locale, $excludeUntranslatedStrings = false)
    {
        $cn = $this->app->make('community_translation/em')->getConnection();
        /* @var \Concrete\Core\Database\Connection\Connection $cn */
        $queryPackageID = (int) $package->getID();
        $queryLocaleID = $cn->quote($locale->getID());
        return $cn->executeQuery(
            "
                select
                    Translatables.tID,
                    tContext,
                    tText,
                    tPlural,
                    tpLocations,
                    tpComments,
                    tReviewed,
                    tText0,
                    tText1,
                    tText2,
                    tText3,
                    tText4,
                    tText5
                from
                    Translatables
                    inner join TranslatablePlaces on Translatables.tID = TranslatablePlaces.tpTranslatable
                    ".(
                        $excludeUntranslatedStrings ?
                        "inner join" :
                        "left join"
                    )." Translations on Translatables.tID = Translations.tTranslatable and 1 = Translations.tCurrent and $queryLocaleID = Translations.tLocale
            where
            TranslatablePlaces.tpPackage = $queryPackageID
            order by
            TranslatablePlaces.tpSort
            "
        );
    }

    /**
     * Get the translations for a specific package, version and locale.
     *
     * @param Package|array $packageOrHandleVersion The package for which you want the translations (a Package instance of an array with handle and version)
     * @param Locale $locale The locale that you want.
     * @param bool $excludeUntranslatedStrings Set to true to filter out untranslated strings.
     *
     * @return \Gettext\Translations
     */
    public function forPackage($packageOrHandleVersion, Locale $locale, $excludeUntranslatedStrings = false)
    {
        if ($packageOrHandleVersion instanceof Package) {
            $package = $packageOrHandleVersion;
        } elseif (is_array($packageOrHandleVersion) && isset($packageOrHandleVersion['handle']) && isset($packageOrHandleVersion['version'])) {
            $package = $this->app->make('community_translation/package')->findOneBy(array(
                'pHandle' => $packageOrHandleVersion['handle'],
                'pVersion' => $packageOrHandleVersion['version'],
            ));
        } elseif (is_array($packageOrHandleVersion) && isset($packageOrHandleVersion[0]) && isset($packageOrHandleVersion[1])) {
            $package = $this->app->make('community_translation/package')->findOneBy(array(
                'pHandle' => $packageOrHandleVersion[0],
                'pVersion' => $packageOrHandleVersion[1],
            ));
        } else {
            $package = null;
        }
        if ($package === null) {
            throw new UserException(t('Invalid translated package specified'));
        }
        $rs = $this->getPackageSelectQuery($package, $locale, $excludeUntranslatedStrings);
        $translations = new \Gettext\Translations();
        $translations->setLanguage($locale->getID());
        $numPlurals = $locale->getPluralCount();
        while (($row = $rs->fetch()) !== false) {
            $translation = new \Gettext\Translation($row['tContext'], $row['tText'], $row['tPlural']);
            if ($row['tReviewed'] === '0') {
                $translation->addFlag('fuzzy');
            }
            foreach (unserialize($row['tpLocations']) as $location) {
                $translation->addReference($location);
            }
            foreach (unserialize($row['tpComments']) as $comment) {
                $translation->addExtractedComment($comment);
            }
            if ($row['tText0'] !== null) {
                $translation->setTranslation($row['tText0']);
                if ($translation->hasPlural()) {
                    switch ($numPlurals) {
                        case 6:
                            $translation->setPluralTranslation($row['tText5'], 4);
                            /* @noinspection PhpMissingBreakStatementInspection */
                        case 5:
                            $translation->setPluralTranslation($row['tText4'], 3);
                            /* @noinspection PhpMissingBreakStatementInspection */
                        case 4:
                            $translation->setPluralTranslation($row['tText3'], 2);
                            /* @noinspection PhpMissingBreakStatementInspection */
                        case 3:
                            $translation->setPluralTranslation($row['tText2'], 1);
                            /* @noinspection PhpMissingBreakStatementInspection */
                        case 2:
                            $translation->setPluralTranslation($row['tText1'], 0);
                            /* @noinspection PhpMissingBreakStatementInspection */
                            break;
                    }
                }
            }
            $translations->append($translation);
        }
        $rs->closeCursor();

        return $translations;
    }
}
