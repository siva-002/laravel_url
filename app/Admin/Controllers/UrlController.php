<?php

namespace App\Admin\Controllers;

use App\Models\Url;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UrlController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Url';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Url());

        $grid->column('id', __('Id'));
        $grid->column('actualurl', __('Actualurl'));
        $grid->column('shortenedurl', __('Shortenedurl'));
        $grid->column('user_id', __('User id'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Url::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('actualurl', __('Actualurl'));
        $show->field('shortenedurl', __('Shortenedurl'));
        $show->field('user_id', __('User id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Url());

        $form->text('actualurl', __('Actualurl'));
        $form->text('shortenedurl', __('Shortenedurl'));
        $form->text('user_id', __('User id'));

        return $form;
    }
}
