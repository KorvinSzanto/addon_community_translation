<?php
namespace CommunityTranslation\Notification\Category;

use CommunityTranslation\Notification\Category;
use Concrete\Core\Mail\Service as MailService;
use Exception;

/**
 * Notification category: some translations need approval.
 */
class TranslationsNeedApproval extends Category
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
        $result = [];
        $locale = $this->app->make(LocaleRepository::class)->findApproved($notificationData['localeID']);
        if ($locale === null) {
            throw new Exception(t('Unable to find the locale with ID %s', $notificationData['localeID']));
        }
        $group = $this->getGroupsHelper()->getAdministrators($locale);
        $result = array_merge($result, $group->getGroupMemberIDs());
        if (empty($result)) {
            $group = $this->getGroupsHelper()->getGlobalAdministrators();
            $result = $group->getGroupMemberIDs();
        }

        return $result;
    }
}
