<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleBlocks;
use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\Behaviors\HandleSlugs;
use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Project;

class ProjectRepository extends ModuleRepository
{
    use HandleBlocks, HandleTranslations, HandleSlugs, HandleMedias, HandleRevisions;

    public function __construct(Project $model)
    {
        $this->model = $model;
    }

    public function afterSave($object, $fields): void
    {
        $this->updateRepeaterMorphMany(
            $object,
            $fields,
            'comments',
            'commentable',
            'Comment',
            'comment'
        );

        $this->updateRepeater(
            $object,
            $fields,
            'links',
        );

        $this->updateRepeaterWithPivot(
            $object,
            $fields,
            'partners',
            ['role'],
            'Partner',
            'project_partner',
        );
        parent::afterSave($object, $fields);
    }

    public function getFormFields($object): array
    {
        $fields = parent::getFormFields($object);

        $fields = $this->getFormFieldsForRepeater(
            $object,
            $fields,
            'comments',
            'Comment',
            'comment'
        );

        $fields = $this->getFormFieldsForRepeater(
            $object,
            $fields,
            'links',
            'Link',
            'link'
        );

        return $this->getFormFieldForRepeaterWithPivot(
            $object,
            $fields,
            'partners',
            ['role'],
            'Partner',
            'project_partner'
        );
    }
}
