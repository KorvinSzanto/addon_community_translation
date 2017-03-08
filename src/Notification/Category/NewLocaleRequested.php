<?php
namespace CommunityTranslation\Notification\Category;

use CommunityTranslation\Notification\Category;
use Concrete\Core\Mail\Service as MailService;
use Exception;

/**
 * Notification category: a new locale has been requested.
 */
class NewLocaleRequested extends Category
{
    /**
     * {@inheritdoc}
     *
     * @see Category::addMailParameters()
     */
    protected function addMailParameters(array $notificationData, MailService $mail)
    {
        throw new Exception('@todo');
    }

    /**
     * {@inheritdoc}
     *
     * @see Category::getRecipientIDs()
     */
    protected function getRecipientIDs(array $notificationData)
    {
        $group = $this->getGroupsHelper()->getGlobalAdministrators();

        return $group->getGroupMemberIDs();
    }
}
