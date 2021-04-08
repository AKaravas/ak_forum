<?php
declare(strict_types=1);

namespace Karavas\AkForum\Domain\Model;


use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/***
 *
 * This file is part of the "Just a forum" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 Aristeidis Karavas <aristeidis.karavas@gmail.com>, Karavas
 *
 ***/
/**
 * FrontendUser
 */
class FrontendUser extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
{
    /**
     * reputation
     *
     * @var int
     */
    protected $reputation = 0;

    /**
     *
     * @var string
     */
    protected $allowedActivity = '';

    /**
     * reportedBy
     *
     * @var ObjectStorage<FrontendUser>
     */
    protected $reportedBy;

    /**
     * visitedBy
     *
     * @var ObjectStorage<FrontendUser>
     */
    protected $visitedBy;

    /**
     * awards
     *
     * @var ObjectStorage<Award>
     */
    protected $awards;

    /**
     * awards
     *
     * @var ObjectStorage<Activity>
     */
    protected $activity;

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects(): void
    {
        $this->reportedBy = new ObjectStorage();
        $this->awards = new ObjectStorage();
        $this->visitedBy = new ObjectStorage();
        $this->activity = new ObjectStorage();
    }

    /**
     * Adds a FrontendUser
     *
     * @param FrontendUser $reportedBy
     * @return void
     */
    public function addReportedBy(FrontendUser $reportedBy): void
    {
        $this->reportedBy->attach($reportedBy);
    }

    /**
     * Removes a FrontendUser
     *
     * @param FrontendUser $reportedByToRemove The FrontendUser to be removed
     * @return void
     */
    public function removeReportedBy(FrontendUser $reportedByToRemove): void
    {
        $this->reportedBy->detach($reportedByToRemove);
    }

    /**
     * Returns the reportedBy
     *
     * @return ObjectStorage<FrontendUser> $reportedBy
     */
    public function getReportedBy(): ?ObjectStorage
    {
        return $this->reportedBy;
    }

    /**
     * Sets the reportedBy
     *
     * @param ObjectStorage<FrontendUser> $reportedBy
     * @return void
     */
    public function setReportedBy(ObjectStorage $reportedBy): void
    {
        $this->reportedBy = $reportedBy;
    }

    /**
     * Returns the reputation
     *
     * @return int $reputation
     */
    public function getReputation(): int
    {
        return $this->reputation;
    }

    /**
     * Sets the reputation
     *
     * @param int $reputation
     * @return void
     */
    public function setReputation(int $reputation): void
    {
        $this->reputation = $reputation;
    }

    /**
     * Adds an award
     *
     * @param Award $awards
     * @return void
     */
    public function addAwards(Award $awards): void
    {
        $this->awards->attach($awards);
    }

    /**
     * Removes a Award
     *
     * @param Award $awardsToRemove The Award to be removed
     * @return void
     */
    public function removeAwards(Award $awardsToRemove): void
    {
        $this->awards->detach($awardsToRemove);
    }

    /**
     * Returns the awards
     *
     * @return ObjectStorage<Award> $awards
     */
    public function getAwards(): ?ObjectStorage
    {
        return $this->awards;
    }

    /**
     * Sets the awards
     *
     * @param ObjectStorage<Award> $awards
     * @return void
     */
    public function setAwards(ObjectStorage $awards): void
    {
        $this->awards = $awards;
    }

    /**
     * Adds an visitedBy
     *
     * @param FrontendUser $visitedBy
     * @return void
     */
    public function addVisitedBy(FrontendUser $visitedBy): void
    {
        $this->visitedBy->attach($visitedBy);
    }

    /**
     * Removes a visited by
     *
     * @param FrontendUser $visitedByToRemove The Award to be removed
     * @return void
     */
    public function removeVisitedBy(FrontendUser $visitedByToRemove): void
    {
        $this->visitedBy->detach($visitedByToRemove);
    }

    /**
     * Returns the visitedBy
     *
     * @return ObjectStorage<FrontendUser> $visitedBy
     */
    public function getVisitedBy(): ?ObjectStorage
    {
        return $this->visitedBy;
    }

    /**
     * Sets the visitedBy
     *
     * @param ObjectStorage<FrontendUser> $visitedBy
     * @return void
     */
    public function setVisitedBy(ObjectStorage $visitedBy): void
    {
        $this->visitedBy = $visitedBy;
    }


    /**
     * Adds an activity
     *
     * @param Activity $activity
     * @return void
     */
    public function addActivity(Activity $activity): void
    {
        $this->activity->attach($activity);
    }

    /**
     * Removes a visited by
     *
     * @param Activity $activityToRemove The Award to be removed
     * @return void
     */
    public function removeActivity(Activity $activityToRemove): void
    {
        $this->activity->detach($activityToRemove);
    }

    /**
     * Returns the activity
     *
     * @return ObjectStorage<Activity> $activity
     */
    public function getActivity(): ?ObjectStorage
    {
        return $this->activity;
    }

    /**
     * Sets the activity
     *
     * @param ObjectStorage<FrontendUser> $activity
     * @return void
     */
    public function setActivity(ObjectStorage $activity): void
    {
        $this->activity = $activity;
    }

    /**
     * @return array
     */
    public function getAllowedActivity(): array
    {
        return explode(',', $this->allowedActivity);
    }

    /**
     * @param array $allowedActivity
     */
    public function setAllowedActivity(array $allowedActivity): void
    {
        $this->allowedActivity = implode(',', $allowedActivity);
    }
}
