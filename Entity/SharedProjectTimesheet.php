<?php


namespace KimaiPlugin\SharedProjectTimesheetsBundle\Entity;

use App\Entity\Project;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="kimai2_shared_project_timesheets",
 *     indexes={
 *          @ORM\Index(columns={"project_id"}),
 *          @ORM\Index(columns={"share_key"}),
 *          @ORM\Index(columns={"project_id", "share_key"}),
 *     }
 * )
 * @ORM\Entity(repositoryClass="KimaiPlugin\SharedProjectTimesheetsBundle\Repository\SharedProjectTimesheetRepository")
 */
class SharedProjectTimesheet
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Project")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Assert\NotNull()
     */
    protected $project;

    /**
     * @var string
     *
     * @ORM\Column(name="share_key", type="string", length=20, nullable=false)
     * @Assert\Length(max=20)
     */
    protected $shareKey;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    protected $password;

    /**
     * @var boolean
     *
     * @ORM\Column(name="entry_user_visible", type="boolean", nullable=false)
     */
    protected $entryUserVisible = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="entry_rate_visible", type="boolean", nullable=false)
     */
    protected $entryRateVisible = false;

    /**
     * @return Project
     */
    public function getProject(): ?Project
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project): SharedProjectTimesheet
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return string
     */
    public function getShareKey(): ?string
    {
        return $this->shareKey;
    }

    /**
     * @param string $shareKey
     * @return SharedProjectTimesheet
     */
    public function setShareKey(string $shareKey): SharedProjectTimesheet
    {
        $this->shareKey = $shareKey;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     * @return SharedProjectTimesheet
     */
    public function setPassword(?string $password): SharedProjectTimesheet
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEntryUserVisible(): bool
    {
        return $this->entryUserVisible;
    }

    /**
     * @param bool $entryUserVisible
     * @return SharedProjectTimesheet
     */
    public function setEntryUserVisible(bool $entryUserVisible): SharedProjectTimesheet
    {
        $this->entryUserVisible = (bool) $entryUserVisible;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEntryRateVisible(): bool
    {
        return $this->entryRateVisible;
    }

    /**
     * @param bool $entryRateVisible
     * @return SharedProjectTimesheet
     */
    public function setEntryRateVisible(bool $entryRateVisible): SharedProjectTimesheet
    {
        $this->entryRateVisible = (bool) $entryRateVisible;

        return $this;
    }

}