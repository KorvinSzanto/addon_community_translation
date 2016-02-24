<?php
namespace Concrete\Package\CommunityTranslation\Src\Git;

/**
 * Represents a git repository to fetch the translatables strings from.
 *
 * @Entity
 * @Table(name="GitRepositories", options={"comment": "Git repositories containing translatable strings"})
 */
class Repository
{
    // Properties

    /**
     * Git repository ID.
     *
     * @Id @Column(type="integer", options={"unsigned": true, "comment": "Git repository ID"})
     * @GeneratedValue(strategy="AUTO")
     *
     * @var int|null
     */
    protected $grID;

    /**
     * Mnemonic name.
     *
     * @Column(type="string", length=100, nullable=false, unique=true, options={"comment": "Mnemonic name"})
     *
     * @var string
     */
    protected $grName;

    /**
     * Package handle ('' for core).
     *
     * @Column(type="string", length=64, nullable=false, options={"comment": "Package handle ('' for core)"})
     *
     * @var string
     */
    protected $grPackage;

    /**
     * Repository remote URL.
     *
     * @Column(type="string", length=255, nullable=false, options={"comment": "Repository remote URL"})
     *
     * @var string
     */
    protected $grURL;

    /**
     * Repository development branch.
     *
     * @Column(type="string", length=255, nullable=false, options={"comment": "Repository development branch"})
     *
     * @var string
     */
    protected $grDevBranch;

    /**
     * Repository development version.
     *
     * @Column(type="string", length=255, nullable=false, options={"comment": "Repository development version"})
     *
     * @var string
     */
    protected $grDevVersion;

    /**
     * Repository tags filter.
     *
     * @Column(type="string", length=255, nullable=false, options={"comment": "Repository tags filter"})
     *
     * @var string
     */
    protected $grTagsFilter;

    /**
     * Path to the web root folder.
     *
     * @Column(type="string", length=255, nullable=false, options={"comment": "Path to the web root folder"})
     *
     * @var string
     */
    protected $grWebRoot;

    // Getters and setters

    /**
     * Get the git repository ID.
     *
     * @return int|null
     */
    public function getID()
    {
        return $this->grID;
    }

    /**
     * Get the mnemonic name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->grName;
    }

    /**
     * Set the mnemonic name.
     *
     * @param string $value
     */
    public function setName($value)
    {
        $this->grName = (string) $value;
    }

    /**
     * Get the package handle ('' for core).
     *
     * @return string
     */
    public function getPackage()
    {
        return $this->grPackage;
    }

    /**
     * Set the package handle ('' for core).
     *
     * @param string $value
     */
    public function setPackage($value)
    {
        $this->grPackage = (string) $value;
    }

    /**
     * Get the repository remote URL..
     *
     * @return string
     */
    public function getURL()
    {
        return $this->grURL;
    }

    /**
     * Set the repository remote URL..
     *
     * @param string $value
     */
    public function setURL($value)
    {
        $this->grURL = (string) $value;
    }

    /**
     * Get the repository development branch.
     *
     * @return string
     */
    public function getDevBranch()
    {
        return $this->grDevBranch;
    }

    /**
     * Set the repository development branch.
     *
     * @param string $value
     */
    public function setDevBranch($value)
    {
        $this->grDevBranch = (string) $value;
    }

    /**
     * Get the repository development version.
     *
     * @return string
     */
    public function getDevVersion()
    {
        return $this->grDevVersion;
    }

    /**
     * Set the repository development version.
     *
     * @param string $value
     */
    public function setDevVersion($value)
    {
        $this->grDevVersion = (string) $value;
    }

    /**
     * Get the repository tags filter.
     *
     * @return string
     */
    public function getTagsFilter()
    {
        return $this->grTagsFilter;
    }

    /**
     * Extracts the info for tags filter.
     *
     * @return null|array('operator' => string, 'version' => string)
     */
    public function getTagsFilterExpanded()
    {
        $result = null;
        $m = null;
        if (preg_match('/^\s*([<>=]+)\s*(\d+(?:\.\d+)?)\s*$/', $this->getTagsFilter(), $m)) {
            switch ($m[1]) {
                case '<=':
                case '<':
                case '=':
                case '>=':
                case '>':
                    $result = array(
                        'operator' => $m[1],
                        'version' => $m[2],
                    );
                    break;
            }
        }

        return $result;
    }
    /**
     * Set the repository tags filter.
     *
     * @param string $value
     */
    public function setTagsFilter($value)
    {
        $this->grTagsFilter = (string) $value;
    }

    /**
     * Get the path to the web root folder.
     *
     * @return string
     */
    public function getWebRoot()
    {
        return $this->grWebRoot;
    }

    /**
     * Set the path to the web root folder.
     *
     * @param string $value
     */
    public function setWebRoot($value)
    {
        $this->grWebRoot = (string) $value;
    }
}
