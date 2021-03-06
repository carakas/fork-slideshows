<?php

namespace Backend\Modules\Slideshows\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Slideshows\Engine\Model as BackendSlideshowsModel;

/**
 * This action will delete a slide
 *
 * @author Jonas De Keukelaere <jonas@sumocoders.be>
 * @author Mathias Helin <mathias@sumocoders.be>
 */
class DeleteSlide extends BackendBaseActionDelete
{
    /**
     * Execute the action
     */
    public function execute()
    {
        $this->id = $this->getParameter('id', 'int');

        // group exists and id is not null?
        if ($this->id !== null && BackendSlideshowsModel::existsSlide($this->id)) {
            parent::execute();

            // get record
            $this->record = BackendSlideshowsModel::getSlide($this->id);

            // delete group
            BackendSlideshowsModel::deleteSlide($this->id);

            // trigger event
            BackendModel::triggerEvent($this->getModule(), 'after_delete', array('id' => $this->id));

            // item was deleted, so redirect
            $redirectURL = BackendModel::createURLForAction('Edit');
            $redirectURL .= '&id=' . $this->record['slideshow_id'];
            $redirectURL .= '&report=deleted&var=' . urlencode($this->record['title']);
            $this->redirect($redirectURL);
        } else {
            // no item found, redirect to the overview with an error
            $this->redirect(BackendModel::createURLForAction('Index') . '&error=non-existing');
        }
    }
}
